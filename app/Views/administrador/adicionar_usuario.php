<div class="row">
    <div class="col-12">
        <?php 
            $this->session = \Config\Services::session();

            if($this->session->getFlashdata("danger")){
                echo '<div style="color:#fff" class="alert alert-danger" role="alert">
                        <strong>Erro! </strong>'.$this->session->getFlashdata("danger").'
                    </div>';
            }else if($this->session->getFlashdata("success")){
                echo '<div style="color:#fff" class="alert alert-success" role="alert">
                        <strong>OK! </strong>'.$this->session->getFlashdata("success").'
                    </div>';
            }else if($this->session->getFlashdata("warning")){
                echo '<div style="color:#fff" class="alert alert-warning" role="alert">
                        <strong>Atenção! </strong>'.$this->session->getFlashdata("warning").'
                    </div>';
            }
        ?>
        <div class="card mb-4">
            <div class="card-header pb-0">
                <!-- <h6>Usuários</h6> -->
            </div>
            <div class="card-body px-5 pt-0 pb-2">
                <?php echo form_open('administrador/cadastrarUsuario',array('name'=>'formCadastrarUsuario', 'id'=>'formCadastrarUsuario'));?>
                <label>Nome</label>
                <div class="mb-3">
                    <input type="name" class="form-control" id="nome" name="nome" required='true' placeholder="Nome" aria-label="Nome">
                </div>
                <label>Usuário AD</label>
                <div class="mb-3">
                    <input type="usuario_ad" class="form-control" id="usuario_ad" name="usuario_ad" placeholder="Usuário AD" aria-label="Usuário AD">
                </div>
                <label>Email</label>
                <div class="mb-3">
                    <input type="email" class="form-control" id="email" name="email" required='true' placeholder="Email" aria-label="Email">
                </div>
                <label>Status</label>
                <div class="mb-3">
                    <select id="status" name="status" required='true' class="form-control" >
                        <?php
                            echo "<option selected value=''>Selecione uma opção</option>";
                            for($i=0; $i<count($status_possiveis);$i++){
                                echo "<option value='".$status_possiveis[$i]["SIGLA_STATUS"]."'>".$status_possiveis[$i]["NOME_STATUS"]."</option>";
                            }
                        ?>
                    </select>
                </div>
                <label>Perfil</label>
                <div class="mb-3">
                    <select id="tipo_perfil" name="tipo_perfil" required='true' class="form-control" >
                        <?php
                            echo "<option selected value=''>Selecione uma opção</option>";
                            for($i=0; $i<count($tipos_perfil);$i++){
                                echo "<option value='".$tipos_perfil[$i]["SIGLA_TIPO_PERFIL"]."'>".$tipos_perfil[$i]["NOME_TIPO_PERFIL"]."</option>";
                            }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label>*O usuário será cadastrado com a senha provisória '123456' e poderá mudar no primeiro acesso.</label>
                </div>
                <div class="text-center">
                    <!-- <button type="button" class="btn bg-gradient-info w-100 mt-4 mb-0">Entrar</button> -->
                <?php 
                    echo form_button(array("class"=>"btn bg-gradient-success w-100 mt-4 mb-0", "type"=>"submit", "content"=>"Cadastrar usuário"));
                    echo form_close();
                ?>
                </div>
            </div>
        </div>
    </div>
</div>