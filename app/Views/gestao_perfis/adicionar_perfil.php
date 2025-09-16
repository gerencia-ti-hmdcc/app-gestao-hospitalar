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
                <?php echo form_open('perfis/cadastrarPerfil',array('name'=>'formCadastrarPerfil', 'id'=>'formCadastrarPerfil'));?>
                <label>Nome</label>
                <div class="mb-3">
                    <input type="name" class="form-control" id="nome" name="nome" required='true' placeholder="Nome" aria-label="Nome">
                </div>
                <label>Sigla</label>
                <div class="mb-3">
                    <input type="name" class="form-control" maxlength="1" id="sigla" name="sigla" required='true' placeholder="Sigla" aria-label="Sigla">
                </div>
                <label>Funções permitidas</label>

                <table class="table align-items-center justify-content-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Módulo</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Função</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for($i=0;$i<count($funcoes_disponiveis_desenvolvidas);$i++){
                                echo    "<tr>
                                            <td>
                                                ".$funcoes_disponiveis_desenvolvidas[$i]["NOME_MODULO"]."
                                            </td>
                                            <td class='font-weight-bolder'>
                                                ".$funcoes_disponiveis_desenvolvidas[$i]["NOME_FUNCAO"]."
                                            </td>
                                            <td class='text-center'>
                                                <input type='checkbox' name='funcoes_liberadas[]' id='funcoes_liberadas[]' value='".$funcoes_disponiveis_desenvolvidas[$i]["ID"]."'>
                                            </td>
                                        </tr>";
                            }
                        ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <!-- <button type="button" class="btn bg-gradient-info w-100 mt-4 mb-0">Entrar</button> -->
                <?php 
                    echo form_button(array("class"=>"btn bg-gradient-success w-100 mt-4 mb-0", "type"=>"submit", "content"=>"Cadastrar perfil"));
                    echo form_close();
                ?>
                </div>
            </div>
        </div>
    </div>
</div>