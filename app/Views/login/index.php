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
  <link rel="apple-touch-icon" sizes="76x76" href=<?php echo base_url("public/assets/img/hmdcc_amarelo.ico");?>>
  <link rel="icon" type="image/png" href=<?php echo base_url("public/assets/img/hmdcc_amarelo.ico");?>>
  <title>HMDCC</title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href=<?php echo base_url("public/assets/css/nucleo-icons.css");?> rel="stylesheet"/> 
  <link href=<?php echo base_url("public/assets/css/nucleo-svg.css");?> rel="stylesheet"/>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script> -->
  <!-- CSS Files -->
  <link id="pagestyle" href=<?php echo base_url("public/assets/css/soft-ui-dashboard.css?v=1.0.3");?> rel="stylesheet" />
  <style>
    :root {
      --largura: 100%;
    }
    img {
      width: var(--largura);
    }
  </style>
</head>

<body class="">
  
  <main class="main-content  mt-0">
    <section>
      <?php echo form_open('login/autenticar');?>

      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
              <?php 
               $this->session = \Config\Services::session();

                  if(session()->getFlashdata("danger")){
                    echo '<div style="color:#fff" class="alert alert-danger" role="alert">
                              <strong>Erro! </strong>'.$this->session->getFlashdata("danger").'
                          </div>';
                  }else if(session()->getFlashdata("success")){
                    echo '<div style="color:#fff" class="alert alert-success" role="alert">
                              <strong>OK! </strong>'.$this->session->getFlashdata("success").'
                          </div>';
                  }else if(session()->getFlashdata("warning")){
                    echo '<div style="color:#fff" class="alert alert-warning" role="alert">
                              <strong>Atenção! </strong>'.$this->session->getFlashdata("warning").'
                          </div>';
                  }
          
              ?>
              <div class="card card-plain mt-6">
                <div class="card-header pb-0 text-left bg-transparent">
                  <!-- <h3 class="font-weight-bolder text-info text-gradient">App HMDCC</h3> -->
                  <img class="" src=<?php echo base_url("public/assets/img/logo/logo1.jpg");?>>
                  <p class="mb-0">Digite seu e-mail e senha para entrar.</p>
                </div>
                <div class="card-body">
                  <form role="form">
                    <label>Email</label>
                    <div class="mb-3">
                      <input type="email" class="form-control" id="email" name="email" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                    </div>
                    <label>Senha</label>
                    <div class="mb-3">
                      <input type="password" id="senha" name="senha" class="form-control" placeholder="Senha" aria-label="Password" aria-describedby="password-addon">
                    </div>
                    <div class="text-center">
                      <!-- <button type="button" class="btn bg-gradient-info w-100 mt-4 mb-0">Entrar</button> -->
                      <?php 
                        echo form_button(array("class"=>"btn bg-gradient-primary-hmdcc text-white w-100 mt-4 mb-0", "type"=>"submit", "content"=>"Entrar"));
                        echo form_close();
                      ?>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('public/assets/img/1.jpg')"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <footer class="footer py-5">
    <div class="container">
      <div class="row">
        <div class="col-8 mx-auto text-center mt-1">
          <p class="mb-0 text-secondary">
            <!-- Copyright © <script>
              document.write(new Date().getFullYear())
            </script>  -->
            Desenvolvido pela <a href="https://www.hmdcc.com.br" class="font-weight-bold" target="_blank">Gerência de Tecnologia e Inovação do HMDCC</a>.
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- -------- END FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
  <!--   Core JS Files   -->
  <script src=<?php echo base_url("public/assets/js/core/popper.min.js");?>></script>
  <script src=<?php echo base_url("public/assets/js/core/bootstrap.min.js");?>></script> 
  <script src=<?php echo base_url("public/assets/js/plugins/perfect-scrollbar.min.js");?>></script>
  <script src=<?php echo base_url("public/assets/js/plugins/smooth-scrollbar.min.js");?>></script>
 <script>

    // var win = navigator.platform.indexOf('Win') > -1;
    // if (win && document.querySelector('#sidenav-scrollbar')) {
    //   var options = {
    //     damping: '0.5'
    //   }
    //   Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    // }
  </script>
  <!-- Github buttons -->
  <!-- <script async defer src="https://buttons.github.io/buttons.js"></script> -->
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src=<?php echo base_url("public/assets/js/soft-ui-dashboard.min.js?v=1.0.3");?>></script>
</body>

</html>