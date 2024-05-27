<aside class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 " id="sidenav-main">
    <div class="sidenav-header" style="text-align:center">
        <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
        <!-- <img src="<?php echo base_url()."$diretorio_raiz"."assets/img/logo/logo_hmdcc.svg";?>" width="50%" class="h-100 m-0"> -->
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto  max-height-vh-100 h-100" id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <?php
          // print_r($_SESSION["usuario_logado"]);
          // exit();

          $html_menu_usuario_perfil = "";
          for($i=0;$i<count($_SESSION["usuario_logado"]["menu_permitido_usuario_perfil"]);$i++){
            if($i==0){
              $titulo_modulo_margin = 2;
            }else{
              $titulo_modulo_margin = 5;
            }

            $funcoes_permitidas_modulo = "";

            for($j=0;$j<count($_SESSION["usuario_logado"]["menu_permitido_usuario_perfil"][$i]["funcoes_permitidas"]);$j++){
              $funcao_permitida = $_SESSION["usuario_logado"]["menu_permitido_usuario_perfil"][$i]["funcoes_permitidas"][$j];
              $funcoes_permitidas_modulo .= '<li class="nav-item">
                                                <a class="nav-link active" href="'.base_url().$diretorio_raiz.$funcao_permitida["LINK_INICIAL_FUNCAO"].'">
                                                  <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                                                      <i class="'.$funcao_permitida["ICONE_FONT_AWESOME"].'"></i>
                                                  </div>
                                                  <span class="nav-link-text ms-1">'.$funcao_permitida["NOME_FUNCAO"].'</span>
                                                </a>
                                              </li>';
            }

            $html_menu_usuario_perfil .= '<li class="nav-item mt-'.$titulo_modulo_margin.'">
                                            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">'.$_SESSION["usuario_logado"]["menu_permitido_usuario_perfil"][$i]["NOME_MODULO"].'</h6>
                                          </li>'.$funcoes_permitidas_modulo;

          }
          echo $html_menu_usuario_perfil;
        ?>


        <!-- <li class="nav-item mt-2">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Ocup. Hospitalar</h6>
        </li>
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
          if(($usuario_sessao["TIPO_PERFIL"]=='A' || $usuario_sessao["TIPO_PERFIL"]=='D' || $usuario_sessao["TIPO_PERFIL"]=='E' || $usuario_sessao["TIPO_PERFIL"]=='I') && $usuario_sessao["ID"]!=31){?>
            <li class="nav-item">
              <a class="nav-link active" href="<?php echo base_url()."$diretorio_raiz"."detalhada";?>">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fa fa-solid fa-hospital fa-2x"></i>
                </div>
                <span class="nav-link-text ms-1">Ocupação Detalhada</span>
              </a>
            </li>
          <?php }?>
          <?php if($usuario_sessao["TIPO_PERFIL"]!='C'){?>  
            <li class="nav-item mt-5">
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
            <?php if($usuario_sessao["TIPO_PERFIL"]=='A' || $usuario_sessao["TIPO_PERFIL"]=='E' || $usuario_sessao["TIPO_PERFIL"]=='D' || $usuario_sessao["TIPO_PERFIL"]=='L'){?>
              <li class="nav-item">
                <a class="nav-link active" href="<?php echo base_url()."$diretorio_raiz"."monitoramento_admissoes/";?>">
                  <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                      <i class="fa fa-bar-chart"></i>
                  </div>
                  <span class="nav-link-text ms-1">Monit. de Admissões</span>
                </a>
              </li>
            <?php }?>
        <?php }?>
        <?php
          $usuario_sessao = $this->session->userdata("usuario_logado");
          if($usuario_sessao["TIPO_PERFIL"]=='A'){?>
            <li class="nav-item mt-5">
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
              <a class="nav-link active" href="<?php echo base_url()."$diretorio_raiz"."metas/";?>">
                <div class="icon icon-shape icon-sm shadow border-radius-md bg-white text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fa fa-bar-chart"></i>
                </div>
                <span class="nav-link-text ms-1">Metas de Admissões</span>
              </a>
            </li>
        <?php }?> -->
      </ul>
    </div>
  </aside>