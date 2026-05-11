<nav class="navbar navbar-main navbar-expand-lg px-0 mx-2 shadow-none border-radius-sm"
    style="background-color: var(--color-dark-grey) !important;" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3 position-relative" style="min-height: 48px !important;">
        <nav aria-label="breadcrumb" id="botao_voltar_menu_navbar" class="d-flex align-items-center">
            <?php
            // Global Back Button Logic
            if (isset($link_pagina) && $link_pagina != 'dashboard' && $link_pagina != 'login' && $link_pagina != 'index') {
                echo '<a href="javascript:;" onclick="history.back()" class="btn bg-gradient-hmdcc text-white mb-0 me-3">
                        <div class="d-flex align-items-center text-xs md:text-lg">
                            <i class="fas fa-arrow-left me-2"></i> Voltar
                        </div>
                      </a>';
            }
            ?>
        </nav>

        <!-- Centered Title -->
        <h6 class="font-weight-bolder mb-0 position-absolute start-50 translate-middle-x text-center w-50 text-white"
            style="white-space: normal; line-height: 1.2;">
            <?php echo isset($nome_pagina) ? "<span class='text-uppercase'><i class='ni ni-building text-lg me-2'></i>" . $nome_pagina . "</span>" : ''; ?>
            <?php echo isset($setor_atend["DS_SETOR_ATENDIMENTO"]) ? "<br/>" . "<span class='text-hmdcc-light-grey text-xs'>" . $setor_atend["DS_SETOR_ATENDIMENTO"] . "</span>" : ''; ?>
            <?php echo isset($linha_cuidado["DS_LINHA_CUIDADO"]) ? "<br/>" . "<span class='text-hmdcc-light-grey text-xs'>" . $linha_cuidado["DS_LINHA_CUIDADO"] . "</span>" : ''; ?>
        </h6>

        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">

            </div>
            <ul class="navbar-nav justify-content-end">
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                            <i class="sidenav-toggler-line bg-white"></i>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>