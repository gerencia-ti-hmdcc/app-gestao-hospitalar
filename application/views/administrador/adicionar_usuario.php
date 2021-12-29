<div class="row">
    <div class="col-12">
        <?php 
            if($this->session->flashdata("danger")){
                echo '<div style="color:#fff" class="alert alert-danger" role="alert">
                        <strong>Erro! </strong>'.$this->session->flashdata("danger").'
                    </div>';
            }else if($this->session->flashdata("success")){
                echo '<div style="color:#fff" class="alert alert-success" role="alert">
                        <strong>OK! </strong>'.$this->session->flashdata("success").'
                    </div>';
            }else if($this->session->flashdata("warning")){
                echo '<div style="color:#fff" class="alert alert-warning" role="alert">
                        <strong>Atenção! </strong>'.$this->session->flashdata("warning").'
                    </div>';
            }
        ?>
        <div class="card mb-4">
            <div class="card-header pb-0">
                <!-- <h6>Usuários</h6> -->
            </div>
            <div class="card-body px-5 pt-0 pb-2">
                <?php echo form_open('cadastrarUsuario',array('name'=>'formCadastrarUsuario', 'id'=>'formCadastrarUsuario'));?>
                <label>Nome</label>
                <div class="mb-3">
                    <input type="name" class="form-control" id="nome" name="nome" required='true' placeholder="Nome" aria-label="Nome">
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
                                echo "<option $c_slc value='".$status_possiveis[$i]."'>".$status_possiveis[$i]."</option>";
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
                                echo "<option $c_slc value='".$tipos_perfil[$i]."'>".$tipos_perfil[$i]."</option>";
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