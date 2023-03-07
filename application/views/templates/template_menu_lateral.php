<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header" style="text-align:center">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <!-- <img src="<?php /*echo base_url()."$diretorio_raiz"."assets/img/logo/logo1.jpg";*/?>" class="h-100 m-0"> -->
        <img src="<?php echo base_url()."$diretorio_raiz"."assets/img/logo/logo_hmdcc.svg";?>" width="50%" class="h-100 m-0">
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100 h-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="<?php echo base_url()."$diretorio_raiz"."dashboard";?>">
            <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                <i class="ni ni-chart-bar-32"></i>
            </div>
            <span class="nav-link-text ms-1">Ocupação</span>
          </a>
        </li>
        <?php
          $usuario_sessao = $this->session->userdata("usuario_logado");
          if($usuario_sessao["TIPO_PERFIL"]=='A' /*|| $usuario_sessao["TIPO_PERFIL"]=='D' || $usuario_sessao["TIPO_PERFIL"]=='G'*/){?>
            <li class="nav-item mt-7">
              <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Prod. Hospitalar</h6>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="<?php echo base_url()."$diretorio_raiz"."admissoes";?>">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fa fa-bar-chart"></i>
                </div>
                <span class="nav-link-text ms-1">Gestão de Admissões</span>
              </a>
            </li>
        <?php }?>
        <?php
          $usuario_sessao = $this->session->userdata("usuario_logado");
          if($usuario_sessao["TIPO_PERFIL"]=='A'){?>
            <li class="nav-item mt-7">
              <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Administrador</h6>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="<?php echo base_url()."$diretorio_raiz"."administrador/usuarios";?>">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fa fa-users"></i>
                </div>
                <span class="nav-link-text ms-1">Usuários</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="<?php echo base_url()."$diretorio_raiz"."administrador/metas_admissoes";?>">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fa fa-bar-chart"></i>
                </div>
                <span class="nav-link-text ms-1">Metas de Admissões</span>
              </a>
            </li>

        <?php }?>
      </ul>
    </div>
  </aside>