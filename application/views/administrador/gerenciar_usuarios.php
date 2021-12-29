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
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Usuários</h6>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" onclick="novoUsuario()" aria-expanded="false"> <!-- id="dropdownTable" data-bs-toggle="dropdown" -->
                                <i class="fa fa-plus-circle text-secondary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <?php echo form_open('editarUsuario',array('name'=>'form_usuarios', 'id'=>'form_usuarios'));?>
                    <table class="table align-items-center justify-content-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Perfil</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Último login</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tabela_usuarios" name="tabela_usuarios">
                            
                        </tbody>
                    </table>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function novoUsuario(){
        document.location.href = "novoUsuario";
    }
</script>