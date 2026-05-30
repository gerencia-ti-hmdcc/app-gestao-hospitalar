<?php

if (isset($diretorio_raiz) && strlen($diretorio_raiz) > 0) {
  $diretorio_raiz = $diretorio_raiz;
} else {
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
  <link rel="icon" type="image/png" href="<?php echo base_url("public/assets/img/hmdcc_amarelo.ico"); ?>">
  <title>
    HMDCC
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="<?php echo base_url("public/assets/css/nucleo-icons.css"); ?>" rel="stylesheet" />
  <link href="<?php echo base_url("public/assets/css/nucleo-svg.css"); ?>" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css"
    integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> -->
  <link href="<?php echo base_url("public/assets/css/nucleo-svg.css"); ?>" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="<?php echo base_url("public/assets/css/soft-ui-dashboard.css?v=1.0.3"); ?>"
    rel="stylesheet" />
  <link href="<?php echo base_url("public/assets/css/premium_style.css"); ?>" rel="stylesheet" />
  <style>
    <?php if ($link_pagina == 'dashboard') { ?>
      th:first-child,
      td:first-child {
        position: sticky;
        left: 0px;
        background-color: white;
      }

      .semPaddingCima {
        padding-top: 0px !important;
      }

      .margemTV {
        margin-left: 1%;
      }

      .redimensionaTabela {
        width: 30% !important;
      }

    <?php } ?>
  </style>
</head>

<body class="g-sidenav-show  bg-gray-100">
  <?php
  if (isset($mostrar_menus)) {
    if ($mostrar_menus == 1) {
      include("template_menu_lateral.php");
    }
  } else {
    include("template_menu_lateral.php");
  }
  ?>
  <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
    <!-- Navbar -->
    <?php
    if (isset($mostrar_menus)) {
      if ($mostrar_menus == 1) {
        include("template_menu.php");
      }
    } else {
      include("template_menu.php");
    }
    ?>
    <!-- End Navbar -->
    <div id='div_pagina_geral' name='div_pagina_geral' class="px-2 container-fluid d-flex flex-column mt-2"
      style="min-height: calc(100vh - 120px);">

      <?php echo view($pagina); ?>
      <?php include("template_footer.php"); ?>
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
  <script src=<?php echo base_url("public/assets/js/core/popper.min.js"); ?>></script>
  <script src=<?php echo base_url("public/assets/js/core/bootstrap.min.js"); ?>></script>
  <script src=<?php echo base_url("public/assets/js/plugins/perfect-scrollbar.min.js"); ?>></script>
  <script src=<?php echo base_url("public/assets/js/plugins/smooth-scrollbar.min.js"); ?>></script>
  <script src=<?php echo base_url("public/assets/js/plugins/chartjs.min.js"); ?>></script>
  <script src=<?php echo base_url("public/js/jquery-3.1.0.js"); ?>></script>
  <script src=<?php echo base_url("public/js/jquery.js"); ?>></script>
  <!-- Global Config and Helpers -->
  <script>
    window.HMDCC_CONFIG = {
      baseUrl: "<?php echo base_url(); ?>",
      currentPage: "<?php echo $link_pagina; ?>",
      userProfile: "<?php echo isset($tipo_perfil) ? $tipo_perfil : ''; ?>",
      urls: {
        percentuaisGerais: "<?php echo site_url('/dashboard/percentuaisGeraisOcupacao'); ?>",
        retornaSetorLoopPainel: "<?php echo site_url('dashboard/retornaSetorLoopPainel'); ?>",
        atualizarVariavelPainelControleSessao: "<?php echo site_url('dashboard/atualizarVariavelPainelControleSessao'); ?>",
        retornaTotaisAvaliacoesVerdeVermelho: "<?php echo base_url('detalhada/retornaTotaisAvaliacoesVerdeVermelho'); ?>",
        retornaDadosLeitoPorAtendimento: "<?php echo base_url('detalhada/retornaDadosLeitoPorAtendimento'); ?>",
        retornaMovimentacoesAtendimento: "<?php echo base_url('detalhada/retornaMovimentacoesAtendimento'); ?>",
        retornaTabelaExames: "<?php echo base_url('detalhada/retornaTabelaExames'); ?>"
      }
    };

    function editarUsuario(id) {
      $("#form_usuarios").append("<input type='hidden' id='usuario_escolhido' name='usuario_escolhido' value='" + id + "'/>")
      document.getElementById("form_usuarios").submit();
    }

    function editarMeta(id) {
      $("#form_metas").append("<input type='hidden' id='meta_escolhida' name='meta_escolhida' value='" + id + "'/>")
      document.getElementById("form_metas").submit();
    }

    function editarPerfil(id) {
      $("#form_perfis").append("<input type='hidden' id='perfil_escolhido' name='perfil_escolhido' value='" + id + "'/>")
      document.getElementById("form_perfis").submit();
    }
  </script>

  <!-- Page Specific Logic -->
  <script src="<?php echo base_url("public/assets/js/page_specific/dashboard.js?v=2.0"); ?>"></script>

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
  <script src="<?php echo base_url("public/assets/js/soft-ui-dashboard.min.js?v=1.0.3"); ?>"></script>
</body>

</html>