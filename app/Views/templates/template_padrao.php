<?php

if(isset($diretorio_raiz) && strlen($diretorio_raiz)>0){
  $diretorio_raiz = $diretorio_raiz;
}else{
  $diretorio_raiz = "";
}
?>
<!--
=========================================================
* Soft UI Dashboard - v1.0.3
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2021 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)

* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="public/assets/img/hmdcc_amarelo.ico">
  <link rel="icon" type="image/png" href="<?php echo base_url("public/assets/img/hmdcc_amarelo.ico");?>">
  <title>
    HMDCC
</title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="<?php echo base_url("public/assets/css/nucleo-icons.css");?>" rel="stylesheet" />
  <link href="<?php echo base_url("public/assets/css/nucleo-svg.css");?>" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> -->
  <link href="<?php echo base_url("public/assets/css/nucleo-svg.css");?>" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="<?php echo base_url("public/assets/css/soft-ui-dashboard.css?v=1.0.3");?>" rel="stylesheet" />
  <style>
    <?php if($link_pagina=='dashboard'){?>
      th:first-child, td:first-child{
        position:sticky;
        left:0px;
        background-color:white;
      }
      .semPaddingCima{
        padding-top:0px !important;
      }
      .margemTV{
        margin-left: 1%;
      }
      .redimensionaTabela{
        width: 30% !important;
      }
    <?php } ?>
  </style>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <?php
    if(isset($mostrar_menus)){
      if($mostrar_menus==1){
        include("template_menu_lateral.php");
      }
    }else{
      include("template_menu_lateral.php");
    }
  ?>
  <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <!-- Navbar -->
    <?php
      if(isset($mostrar_menus)){ 
        if($mostrar_menus==1){
          include("template_menu.php");
        }
      }else{
        include("template_menu.php");
      }
    ?>
    <!-- End Navbar -->
    <div id='div_pagina_geral' name='div_pagina_geral' class="container-fluid py-4">
      
       <?php echo view($pagina);?> 
      <?php include("template_footer.php");?>
    </div>
  </main>
    <!-- MENU DE CONFIGURAÇÃO FLUTUANTE -->
    <!-- <div class="fixed-plugin">
      <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="fa fa-cog py-2"> </i>
      </a>
      <div class="card shadow-lg">
        <div class="card-header pb-0 pt-3 ">
          <div class="float-start">
            <h5 class="mt-3 mb-0">Soft UI Configurator</h5>
            <p>See our dashboard options.</p>
          </div>
          <div class="float-end mt-4">
            <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                <i class="fa fa-close"></i>
            </button>
          </div>
        </div>
        <hr class="horizontal dark my-1">
        <div class="card-body pt-sm-3 pt-0">
          
            <div>
            <h6 class="mb-0">Sidebar Colors</h6>
            </div>
            <a href="javascript:void(0)" class="switch-trigger background-color">
            <div class="badge-colors my-2 text-start">
                <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-dark" id='cor_padrao_icone' name='cor_padrao_icone' data-color="dark" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
            </div>
            </a>
            
            <div class="mt-3">
            <h6 class="mb-0">Sidenav Type</h6>
            <p class="text-sm">Choose between 2 different sidenav types.</p>
            </div>
            <div class="d-flex">
            <button class="btn bg-gradient-primary w-100 px-3 mb-2 active" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
            <button class="btn bg-gradient-primary w-100 px-3 mb-2 ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
            </div>
            <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
            
            <div class="mt-3">
            <h6 class="mb-0">Navbar Fixed</h6>
            </div>
            <div class="form-check form-switch ps-0">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
            </div>
            <hr class="horizontal dark my-sm-4">
            <a class="btn bg-gradient-dark w-100" href="https://www.creative-tim.com/product/soft-ui-dashboard-pro">Free Download</a>
            <a class="btn btn-outline-dark w-100" href="https://www.creative-tim.com/learning-lab/bootstrap/license/soft-ui-dashboard">View documentation</a>
            <div class="w-100 text-center">
            <a class="github-button" href="https://github.com/creativetimofficial/soft-ui-dashboard" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star creativetimofficial/soft-ui-dashboard on GitHub">Star</a>
            <h6 class="mt-3">Thank you for sharing!</h6>
            <a href="https://twitter.com/intent/tweet?text=Check%20Soft%20UI%20Dashboard%20made%20by%20%40CreativeTim%20%23webdesign%20%23dashboard%20%23bootstrap5&amp;url=https%3A%2F%2Fwww.creative-tim.com%2Fproduct%2Fsoft-ui-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
                <i class="fab fa-twitter me-1" aria-hidden="true"></i> Tweet
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=https://www.creative-tim.com/product/soft-ui-dashboard" class="btn btn-dark mb-0 me-2" target="_blank">
                <i class="fab fa-facebook-square me-1" aria-hidden="true"></i> Share
            </a>
            </div>
        </div>
      </div>
    </div> -->
  <!--   Core JS Files   -->
  <script src=<?php echo base_url("public/assets/js/core/popper.min.js");?>></script>
  <script src=<?php echo base_url("public/assets/js/core/bootstrap.min.js");?>></script> 
  <script src=<?php echo base_url("public/assets/js/plugins/perfect-scrollbar.min.js");?>></script>
  <script src=<?php echo base_url("public/assets/js/plugins/smooth-scrollbar.min.js");?>></script>
  <script src=<?php echo base_url("public/assets/js/plugins/chartjs.min.js");?>></script> 
  <script src=<?php echo base_url("public/js/jquery-3.1.0.js");?> ></script>
  <script src=<?php echo base_url("public/js/jquery.js");?>></script>
  <script>
    $(document).ready(function(){
        //$("#cor_padrao_icone").click();
        if('<?php echo $link_pagina;?>'=='dashboard'){
          
          $("#tabela_detalhes").hide();
          $.ajax({
            url : "<?php echo site_url('/dashboard/percentuaisGeraisOcupacao');?>",
            type : 'POST',
            dataType: "JSON",
            success : function(data){
              var result  = data;
              //result.push({teste:'teste'});
              var htmlPercentual = "";
              var dataSets_grafico = [];
              var percent = 0.0;
              var livres_1 = 0;
              var temp_indisponiveis = 0;
              var outros_leitos      = 0;
              var unidades_reservadas = 0;
              for(var i = 0; i<result.length; i++){
                unidades_reservadas = parseInt(result[i].NR_UNIDADES_RESERVADAS);
                temp_indisponiveis = parseInt(result[i].QT_UNIDADE_MANUTENCAO)+parseInt(result[i].QT_UNIDADES_ISOLAMENTO);
                outros_leitos      = parseInt(unidades_reservadas)+parseInt(result[i].NR_UNIDADES_HIGIENIZACAO)+parseInt(result[i].QT_UNIDADES_ALTA)+parseInt(result[i].NR_UNID_AGUARD_HIGIEN);
                if(parseInt(result[i].CD_CLASSIF_SETOR)==129 || (parseInt(result[i].CD_CLASSIF_SETOR)==145) || (parseInt(result[i].CD_CLASSIF_SETOR)==83)){
                  percent   = (((parseInt(result[i].NR_UNID_OCUP)) / parseInt(result[i].NR_UNIDADES_SETOR))*100.00).toFixed(2);
                  livres_1  = parseInt(result[i].NR_UNIDADES_SETOR) - parseInt(result[i].NR_UNID_OCUP) - (temp_indisponiveis + outros_leitos); /*- parseInt(result[i].NR_UNIDADES_HIGIENIZACAO) - parseInt(result[i].NR_UNID_AGUARD_HIGIEN) - parseInt(result[i].QT_UNIDADES_ALTA)*/
                  if(livres_1<0 && parseInt(result[i].CD_CLASSIF_SETOR)==129){
                    //ALTERAÇÃO REALIZADA POR CONTA DO NÚMERO NEGATIVO EM "LEITOS LIVRES" - O NUMERO DE OCUPADOS VEM COM DISTINCT POR LEITO DO BANCO E NO CÓDIGO ABATE-SE AGORA A QUANTIDADE NEGATIVA DOS LEITOS RESERVADOS
                    unidades_reservadas = unidades_reservadas - (livres_1*-1);
                    livres_1            = 0;
                    outros_leitos       = parseInt(unidades_reservadas)+parseInt(result[i].NR_UNIDADES_HIGIENIZACAO)+parseInt(result[i].QT_UNIDADES_ALTA)+parseInt(result[i].NR_UNID_AGUARD_HIGIEN);
                  }
                }else{
                  percent   = parseFloat(result[i].PR_OCUPACAO).toFixed(2);
                  livres_1  = result[i].NR_UNIDADES_LIVRES;
                }
                <?php if($link_pagina=='dashboard'){ if($tipo_perfil=='P'){?>
                  var margem_baixo_acerto_tv = 2;  
                  var espaco_card_acerto_tv = 2;
                <?php }else{ ?>
                  var margem_baixo_acerto_tv = 4;  
                  var espaco_card_acerto_tv = 3;
                <?php } } ?>
              
                <?php if($link_pagina=='dashboard'){ if($tipo_perfil=='P'){?>
                  htmlPercentual += '<div name="cardGeral_'+result[i].CD_CLASSIF_SETOR.toString()+'" id="cardGeral_'+result[i].CD_CLASSIF_SETOR.toString()+'" class="cardGeral col-xl-4 col-sm-12 mb-xl-'+margem_baixo_acerto_tv.toString()+' mb-'+margem_baixo_acerto_tv.toString()+'">'+
                                      '<div class="card cursor-pointer" onclick="abrirDivDetalhes('+parseInt(result[i].CD_CLASSIF_SETOR)+')">'+
                                        '<div class="card-body p-'+espaco_card_acerto_tv.toString()+'">'+
                                          '<div class="row">'+
                                          '<div class="numbers col-12">'+
                                            '<div style="float: left" class="col-9">'+
                                            '<p class="text-sm mb-0 text-capitalize" id="titulo'+result[i].CD_CLASSIF_SETOR+'" name="titulo'+result[i].CD_CLASSIF_SETOR+'" font-weight-bold">'+result[i].DS_SETOR_ATENDIMENTO+'</p>'+
                                            '<h5 class="font-weight-bolder mb-0">'+percent+'%</h5>'+
                                            '</div>'+
                                            '<div style="float: left" class="col-3 text-end">'+
                                            '<div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">'+
                                              '<i class="ni ni-building text-lg opacity-10" aria-hidden="true"></i>'+
                                              '<input id="id_area_antigo" name="id_area_antigo" value="'+parseInt(result[i].CD_CLASSIF_SETOR)+'" type="hidden"/>'+
                                            '</div>'+
                                            '</div>'+
                                          '</div>'+   
                                          '<div class="numbers col-12">'+
                                            '<table name="tabelaGeral_'+result[i].CD_CLASSIF_SETOR.toString()+'" id="tabelaGeral_'+result[i].CD_CLASSIF_SETOR.toString()+'" width="100%" class="text-sm">'+
                                            '<tr>'+
                                              '<td class="font-weight-bold" style="color: green"><i class="fa fa-check text-success"></i> Livres</td>'+
                                              '<td class="font-weight-bold" style="color: green">'+livres_1+'</td>'+
                                              '<td class="font-weight-bold" style="color: #2c387e;padding-left:30px"><i class="fas fa-asterisk text-info" style="color: #2c387e !important"></i> Resv./ Hig./ Alta</td>'+
                                              '<td class="font-weight-bold" style="color: #2c387e;padding-left:30px">'+outros_leitos+'</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                              '<td class="font-weight-bold" style="color: red"><i class="fas fa-ban text-danger"></i> Ocupados</td>'+
                                              '<td class="font-weight-bold" style="color: red">'+result[i].NR_UNID_OCUP+'</td>'+
                                              '<td class="font-weight-bold" style="color: #ffa500;padding-left:30px"><i class="fas fa-hourglass-half text-warning"></i> Indisponíveis</td>'+
                                              '<td class="font-weight-bold" style="color: #ffa500;padding-left:30px">'+temp_indisponiveis+'</td>'+
                                            '</tr>'+
                                            '<tr>'+
                                              '<td class="font-weight-bold"><i class="fa fa-hospital"></i> Total</td>'+
                                              '<td class="font-weight-bold">'+result[i].NR_UNIDADES_SETOR+'</td>'+
                                              '<td colspan="2" class="font-weight-bold"></td>'+
                                            '</tr>'+
                                            '</table>'+
                                          '</div>'+   
                                          '</div>'+
                                        '</div>'+
                                      '</div>'+
                                    '</div>';
                <?php }else{ ?>
                  htmlPercentual += '<div class="col-xl-4 flex flex-wrap col-sm-12 mb-xl-'+margem_baixo_acerto_tv.toString()+' mb-'+margem_baixo_acerto_tv.toString()+'">'+
                                    '<div class="card cursor-pointer flex flex-wrap w-full" onclick="abrirDivDetalhes('+parseInt(result[i].CD_CLASSIF_SETOR)+')">'+
                                        '<div class="card-body p-'+espaco_card_acerto_tv.toString()+'">'+
                                            '<div class="row">'+
                                                '<div class="col-9">'+
                                                    '<div class="numbers">'+
                                                      '<p class="text-sm mb-0 text-capitalize" id="titulo'+result[i].CD_CLASSIF_SETOR+'" name="titulo'+result[i].CD_CLASSIF_SETOR+'" font-weight-bold">'+result[i].DS_SETOR_ATENDIMENTO+'</p>'+
                                                      '<h5 class="font-weight-bolder mb-0">'+percent+'%</h5>'+
                                                      '<div class="text-sm">'+
                                                        '<i class="fa fa-hospital"></i>'+
                                                        '<span class="font-weight-bold"> Total : '+result[i].NR_UNIDADES_SETOR+'</span><br />'+
                                                        '<i class="fa fa-check text-success"></i>'+
                                                        '<span class="font-weight-bold" style="color: green"> Livres : '+livres_1+'</span><br />'+
                                                        '<i class="fas fa-ban text-danger"></i>'+
                                                        '<span class="font-weight-bold" style="color: red"> Ocupados : '+result[i].NR_UNID_OCUP+'</span><br />'+
                                                        '<i class="fas fa-hourglass-half text-warning"></i>'+
                                                        '<span class="font-weight-bold" style="color: #ffa500"> Indisponíveis : '+temp_indisponiveis+'</span><br />'+
                                                        '<i class="fas fa-asterisk text-info" style="color: #2c387e !important"></i>'+
                                                        '<span class="font-weight-bold" style="color: #2c387e"> Reserv./ Higien./ Alta : '+outros_leitos+'</span>'+
                                                      '</div>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-3 text-end">'+
                                                    '<div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">'+
                                                      '<i class="ni ni-building text-lg opacity-10" aria-hidden="true"></i>'+
                                                      '<input id="id_area_antigo" name="id_area_antigo" value="'+parseInt(result[i].CD_CLASSIF_SETOR)+'" type="hidden"/>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>';
                <?php } } ?>

                dataSets_grafico.push({
                  label: result[i].DS_SETOR_ATENDIMENTO,
                  tension: 0.4,
                  borderWidth: 0,
                  borderRadius: 4,
                  borderSkipped: false,
                  backgroundColor: gerar_cor(),
                  data: [parseFloat(percent).toFixed(2)],
                  maxBarThickness: 50
                });
              }
              var d = new Date(result[0].DT_ATUALIZACAO);
              var curr_day = d.getDate(result[0].DT_ATUALIZACAO);
              var curr_month = d.getMonth(result[0].DT_ATUALIZACAO);
              var curr_year = d.getFullYear(result[0].DT_ATUALIZACAO);
              var curr_hour = d.getHours(result[0].DT_ATUALIZACAO);
              var curr_min = d.getMinutes(result[0].DT_ATUALIZACAO);
              var curr_sec = d.getSeconds(result[0].DT_ATUALIZACAO);
              curr_month++ ; // In js, first month is 0, not 1
              year_2d = curr_year.toString().substring(2, 4)

              if(isNaN(curr_day) || isNaN(curr_hour)){//SAFARI E IE - HORÁRIO ESTAVA VINDO NULO EM IPHONES (13/04/22) - RESOLVIDO
                var hora_iphone = result[0].DT_ATUALIZACAO.split(" ");
                hora_iphone = hora_iphone[1];
                $("#data_ult_att").html("<small>Atualizado em: "+result[0].DT_ATUALIZACAO.substr(0, 10).split('-').reverse().join('/')+" "+hora_iphone+"</small>");
              }else{
                $("#data_ult_att").html("<small>Atualizado em: "+String(curr_day).padStart(2, "0") +"/"+ String(curr_month).padStart(2, "0") +"/"+ year_2d+" "+String(curr_hour).padStart(2, "0")+":"+String(curr_min).padStart(2, "0")+"</small>");
              }

              $("#divPercentualGeral").html(htmlPercentual);
              var ctx = document.getElementById("chart-bars").getContext("2d");

              new Chart(ctx, {
                type: "bar",
                data: {
                  labels: ["Ocupação"],
                  datasets: dataSets_grafico,
                },
                options: {
                  responsive: true,
                  maintainAspectRatio: false,
                  plugins: {
                    legend: {
                      display: true,
                      labels: {
                        color: '#fff'
                      }
                    }
                  },
                  interaction: {
                    intersect: false,
                    mode: 'index',
                  },
                  scales: {
                    y: {
                      grid: {
                        drawBorder: true,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: true,
                      },
                      ticks: {
                        suggestedMin: 0,
                        suggestedMax: 500,
                        beginAtZero: true,
                        padding: 15,
                        font: {
                          size: 14,
                          family: "Open Sans",
                          style: 'normal',
                          lineHeight: 2
                        },
                        color: "#fff"
                      },
                    },
                    x: {
                      grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false
                      },
                      ticks: {
                        display: false
                      },
                    },
                  },
                },
              });
            },
            error : function(data){
                alert('erro');
            }
          });
          <?php if($link_pagina=='dashboard'){ if($tipo_perfil=='P'){ ?>
            $('#div_pagina_geral').addClass('semPaddingCima');
            setTimeout(function(){
              $.ajax({
                url : "<?php echo site_url('dashboard/retornaSetorLoopPainel')?>",
                type : 'POST',
                data: {"atual" : $("#painel_variavel_controle").val()},
                dataType: "json",
                success : function(data){
                  var proximo_att = data.PROXIMO;
                  $.ajax({
                    url : "<?php echo site_url('dashboard/atualizarVariavelPainelControleSessao')?>",
                    type : 'POST',
                    data: {"proximo" : proximo_att},
                    dataType: "json",
                    success : function(a){
                      //abrirDivDetalhes(data.PROXIMO);
                      $(".cardGeral").hide();
                      $("#cardGeral_"+$("#painel_variavel_controle").val().toString()).removeClass("col-xl-4").addClass("col-xl-4 margemTV");
                      // $("#tabelaGeral_"+$("#painel_variavel_controle").val().toString()).addClass("redimensionaTabela");
                      $("#cardGeral_"+$("#painel_variavel_controle").val().toString()).show();
                      abrirDivDetalhes($("#painel_variavel_controle").val());
                    },
                    error : function(data){
                      alert('Não foi possível definir próximo detalhe!');
                    }
                  });
                },
                error : function(data){
                  alert('Não foi possível retornar detalhe atual!');
                }
              });
            },90000);/*150000*/
          <?php } } ?>
        }else if('<?php echo $link_pagina;?>'=='avaliacoesVerdeVermelho'){
          let html_leito      = "";
          let nr_atendimento  = $("#nr_atendimento_id").val();
          $.ajax({
            url : "<?php echo base_url('detalhada/retornaTotaisAvaliacoesVerdeVermelho');?>",
            type : 'POST',
            data: 
            {
                "nr_atendimento" : nr_atendimento
            },
            dataType: "json",
            success : function(totaisVerdeVemelho){
              let html_totais_verde_vermelho  = "";

              if(parseInt(totaisVerdeVemelho.total)>0){
                  let regra3_verde                = Math.round(totaisVerdeVemelho.porcentagem_verde)*255/100
                  let regra3_vermelho             = Math.round(totaisVerdeVemelho.porcentagem_vermelho)*255/100;

                  html_totais_verde_vermelho =    "<tr><td colspan='2'></td></tr>"+
                                                  "<tr>" +
                                                      "<td class='font-weight-bold text-wrap text-xs'>" +
                                                          "Total de avaliações" +
                                                      "</td>" +
                                                      "<td class='text-wrap text-end justify-content-end text-xs'>" +
                                                          totaisVerdeVemelho.total+
                                                      "</td>" +
                                                  "</tr>"+
                                                  "<tr>" +
                                                      "<td class='font-weight-bold text-wrap text-xs'>" +
                                                          "Total verde" +
                                                      "</td>" +
                                                      "<td class='text-wrap text-end justify-content-end text-xs'>" +
                                                          totaisVerdeVemelho.total_verde+
                                                      "</td>" +
                                                  "</tr>"+
                                                  "<tr>" +
                                                      "<td class='font-weight-bold text-wrap text-xs'>" +
                                                          "% verde" +
                                                      "</td>" +
                                                      "<td class='text-wrap text-end justify-content-end text-xs'>" +
                                                          parseFloat(totaisVerdeVemelho.porcentagem_verde).toFixed(2)+"%"+
                                                      "</td>" +
                                                  "</tr>"+
                                                  "<tr>" +
                                                      "<td class='font-weight-bold text-wrap text-xs'>" +
                                                          "Total vermelho" +
                                                      "</td>" +
                                                      "<td class='text-wrap text-end justify-content-end text-xs'>" +
                                                          totaisVerdeVemelho.total_vermelho+
                                                      "</td>" +
                                                  "</tr>"+
                                                  "<tr>" +
                                                      "<td class='font-weight-bold text-wrap text-xs'>" +
                                                          "% vermelho" +
                                                      "</td>" +
                                                      "<td class='text-wrap text-end justify-content-end text-xs'>" +
                                                          parseFloat(totaisVerdeVemelho.porcentagem_vermelho).toFixed(2)+"%"+
                                                      "</td>" +
                                                  "</tr>"+
                                                  "<tr>" +
                                                      "<td colspan='2' class='text-wrap justify-content-center' style='text-align:-webkit-center'>" +
                                                          "<div style='background-color: rgb("+regra3_vermelho+" "+regra3_verde+" 0); border-radius:50%; width:50px;height:50px' ></div>"+
                                                      "</td>" +
                                                  "</tr>";

                  // $("#grafico_quantidade_verdes_vermelhos").html();
              }
              $.ajax({
                  url : "<?php echo base_url('detalhada/retornaDadosLeitoPorAtendimento');?>",
                  type : 'POST',
                  data: 
                  {
                      "nr_atendimento" : nr_atendimento
                  },
                  dataType: "json",
                  success : function(result){
          
                      $.ajax({
                          url : "<?php echo base_url('detalhada/retornaMovimentacoesAtendimento');?>",
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
          
                              let data_nascimento     = result["dt_nascimento"].split("/");
                              let idade_paciente      = idade(data_nascimento[2],data_nascimento[1],data_nascimento[0]); 
          
                              /*INÍCIO PREENCHIMENTO TABELA DE AVALIAÇÃO VERDE/VERMELHO*/
          
                              if(result["ds_verde_ou_vermelho"] && result["cd_agrupamento"]!=4){
                                  let cor         = "";
                                  if(result["ds_verde_ou_vermelho"].trim().toUpperCase()=="VERDE"){
                                      cor = "#00ff00";
                                  }else{
                                      cor = "#ff0000";
                                  }
          
                                  if(result["ds_motivo_vermelho"].length>0){
                                      
                                      motivo_vermelho = "<tr>" +
                                                              "<td class='font-weight-bold text-wrap'>" +
                                                                  "Motivo" +
                                                              "</td>" +
                                                              "<td class='text-wrap text-justify'>" +
                                                                  result["ds_motivo_vermelho"]+
                                                              "</td>" +
                                                          "</tr>";
                                  }
                                  
                                  conteudo_template_avaliacao =   "<table class='table align-items-center justify-content-center' width='100%'>"+
                                                                      html_totais_verde_vermelho+
                                                                      "<tr>" +
                                                                          "<td colspan='2' class='text-center text-uppercase font-weight-bold text-wrap'>" +
                                                                              "Última Avaliação" +
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
                                                                                  result["ds_verde_ou_vermelho"].trim().toUpperCase()+
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
                              "</table>"+
                              "<div class='bg-gradient border-radius-lg pe-1'>"+
                                "<div class='chart'>"+
                                    "<canvas name='grafico_quantidade_verdes_vermelhos' id='grafico_quantidade_verdes_vermelhos' class='chart-canvas' height='250'></canvas>"+
                                "</div>"+
                              "</div>"+
                              conteudo_template_avaliacao+
                              html_movimentacoes_atendimento;

                              $("#info_principal").html(html_leito);

                              let dataset_verde_vermelho = [];

                              dataset_verde_vermelho.push({
                                label: "Verdes",
                                tension: 0.4,
                                borderWidth: 0,
                                borderRadius: 4,
                                borderSkipped: false,
                                backgroundColor: "#00ff00",
                                data: [parseInt(totaisVerdeVemelho.total_verde)],
                                maxBarThickness: 50
                              });

                              dataset_verde_vermelho.push({
                                label: "Vermelhos",
                                tension: 0.4,
                                borderWidth: 0,
                                borderRadius: 4,
                                borderSkipped: false,
                                backgroundColor: "#ff0000",
                                data: [parseInt(totaisVerdeVemelho.total_vermelho)],
                                maxBarThickness: 50
                              });

                              var ctx = document.getElementById("grafico_quantidade_verdes_vermelhos").getContext("2d");

                              new Chart(ctx, {
                                type: "bar",
                                data: {
                                  labels: ["Verdes e Vermelhos"],
                                  datasets: dataset_verde_vermelho,
                                },
                                options: {
                                  responsive: true,
                                  maintainAspectRatio: false,
                                  plugins: {
                                    legend: {
                                      display: true,
                                      labels: {
                                        color: '#000'
                                      }
                                    }
                                  },
                                  interaction: {
                                    intersect: false,
                                    mode: 'index',
                                  },
                                  scales: {
                                    y: {
                                      grid: {
                                        drawBorder: true,
                                        display: true,
                                        drawOnChartArea: true,
                                        drawTicks: true,
                                      },
                                      ticks: {
                                        suggestedMin: 0,
                                        suggestedMax: 500,
                                        beginAtZero: true,
                                        padding: 15,
                                        font: {
                                          size: 14,
                                          family: "Open Sans",
                                          style: 'normal',
                                          lineHeight: 2
                                        },
                                        color: "#000"
                                      },
                                    },
                                    x: {
                                      grid: {
                                        drawBorder: true,
                                        display: true,
                                        drawOnChartArea: true,
                                        drawTicks: true
                                      },
                                      ticks: {
                                        display: true
                                      },
                                    },
                                  },
                                },
                              });
                              // $("#modal_info").modal('show');
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
            },
            error : function(data){
                alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
            }
          });
        }else if('<?php echo $link_pagina;?>'=='historicoEvolucoesPaciente' || '<?php echo $link_pagina;?>'=='historicoInterconsultasPaciente' || '<?php echo $link_pagina;?>'=='historicoExamesLabPaciente' || '<?php echo $link_pagina;?>'=='historicoExamesImagemPaciente'){
          let nr_atendimento  = $("#nr_atendimento_id").val();
          $.ajax({
            url : "<?php echo base_url('detalhada/retornaDadosLeitoPorAtendimento');?>",
            type : 'POST',
            data: 
            {
                "nr_atendimento" : nr_atendimento
            },
            dataType: "json",
            success : function(result){
    
                $.ajax({
                    url : "<?php echo base_url('detalhada/retornaMovimentacoesAtendimento');?>",
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
    
                        let conteudo_template_avaliacao     = "";
                        let html_movimentacoes_atendimento  = "";
    
                        let data_nascimento     = result["dt_nascimento"].split("/");
                        let idade_paciente      = idade(data_nascimento[2],data_nascimento[1],data_nascimento[0]); 
    
                       
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
                        "</table>"+
                        conteudo_template_avaliacao+
                        html_movimentacoes_atendimento;

                        if('<?php echo $link_pagina;?>'=='historicoExamesImagemPaciente'){
                          $.ajax({
                            url : "<?php echo base_url('detalhada/retornaTabelaExames');?>",
                            type : 'POST',
                            data: 
                            {
                                "nr_prontuario" : $("#nr_prontuario_id").val()
                            },
                            dataType: "json",
                            success : function(resultadoTabelaExames){
                              let html_adicional =  resultadoTabelaExames;
                              html_leito+= resultadoTabelaExames;
                              $("#info_principal").html(html_leito);
                            },error: function(data){
                                alert('Não foi possível abrir o detalhamento. Confira sua conexão!');
                            }
                          });
                        }else{
                          $("#info_principal").html(html_leito);
                        }

                        // $("#modal_info").modal('show');
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
    });

    function editarUsuario(id){
      $("#form_usuarios").append("<input type='hidden' id='usuario_escolhido' name='usuario_escolhido' value='"+id+"'/>")
      document.getElementById("form_usuarios").submit();
    }

    function editarMeta(id){
      $("#form_metas").append("<input type='hidden' id='meta_escolhida' name='meta_escolhida' value='"+id+"'/>")
      document.getElementById("form_metas").submit();
    }

    function editarPerfil(id){
      $("#form_perfis").append("<input type='hidden' id='perfil_escolhido' name='perfil_escolhido' value='"+id+"'/>")
      document.getElementById("form_perfis").submit();
    }

    function luminanace(r, g, b) {
      var a = [r, g, b].map(function (v) {
          v /= 255;
          return v <= 0.03928
              ? v / 12.92
              : Math.pow( (v + 0.055) / 1.055, 2.4 );
      });
      return a[0] * 0.2126 + a[1] * 0.7152 + a[2] * 0.0722;
    }

    function contrast(rgb1, rgb2) {
        return (luminanace(rgb1[0], rgb1[1], rgb1[2]) + 0.05)
            / (luminanace(rgb2[0], rgb2[1], rgb2[2]) + 0.05);
    }

    //contrast([0, 0, 0], [255, 255, 0]); // 1.074 for yellow
    // minimal recommended contrast ratio is 4.5, or 3 for larger font-sizes

    function gerar_cor(opacidade = 1) {
      let r = parseInt(Math.random() * 255);
      let g = parseInt(Math.random() * 255);
      let b = parseInt(Math.random() * 255);
      if(contrast([0, 0, 0], [r, g, b])>0.0){
        //console.log(contrast([0, 0, 0], [r, g, b]));
        return `rgba(${r}, ${g}, ${b}, ${opacidade})`;
      }else{
        gerar_cor(opacidade);
      }
      //return `rgba(${r}, ${g}, ${b}, ${opacidade})`;
    }
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="<?php echo base_url("public/assets/js/soft-ui-dashboard.min.js?v=1.0.3");?>"></script>
</body>

</html>