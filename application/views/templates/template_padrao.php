<?php
defined('BASEPATH') OR exit('No direct script access allowed');
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
  <link rel="apple-touch-icon" sizes="76x76" href="<?php echo $diretorio_raiz;?>assets/img/hmdcc2.ico">
  <link rel="icon" type="image/png" href="<?php echo $diretorio_raiz;?>assets/img/hmdcc2.ico">
  <title>
    HMDCC
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="<?php echo $diretorio_raiz;?>assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="<?php echo $diretorio_raiz;?>assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="<?php echo $diretorio_raiz;?>assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="<?php echo $diretorio_raiz;?>assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
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
      <?php $this->load->view($pagina);?>
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
  <script src="<?php echo $diretorio_raiz;?>assets/js/core/popper.min.js"></script>
  <script src="<?php echo $diretorio_raiz;?>assets/js/core/bootstrap.min.js"></script>
  <script src="<?php echo $diretorio_raiz;?>assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="<?php echo $diretorio_raiz;?>assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="<?php echo $diretorio_raiz;?>assets/js/plugins/chartjs.min.js"></script>
  <script src="<?php echo $diretorio_raiz;?>user_guide/_static/jquery-3.1.0.js"></script>
  <script src="<?php echo $diretorio_raiz;?>user_guide/_static/jquery.js"></script>
  <script>
    $(document).ready(function(){
        //$("#cor_padrao_icone").click();
        if('<?php echo $link_pagina;?>'=='dashboard'){
          $("#tabela_detalhes").hide();
          $.ajax({
            url : "<?php echo site_url('/dashboard/percentuaisGeraisOcupacao')?>",
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
  <script src="<?php echo $diretorio_raiz;?>assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
</body>

</html>