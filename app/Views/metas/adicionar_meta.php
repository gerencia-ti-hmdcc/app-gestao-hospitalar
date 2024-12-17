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
                <?php echo form_open('metas/cadastrarMeta',array('name'=>'formCadastrarMeta', 'id'=>'formCadastrarMeta'));?>
                <label>Ano</label>
                <div class="mb-3">
                    <input type="text" class="form-control" id="ano" name="ano" required='true' placeholder="Ano" aria-label="Ano">
                </div>
                <label>Quadrimestre</label>
                <div class="mb-3">
                    <select id="quadrimestre" name="quadrimestre" required='true' class="form-control" >
                        <?php
                            echo "<option selected value=''>Selecione uma opção</option>";
                            for($i=1; $i<=3;$i++){
                                echo "<option value='".$i."'>".$i."º</option>";
                            }
                        ?>
                    </select>    
                </div>
                <label>Tipo</label>
                <div class="mb-3">
                    <select id="tipo" name="tipo" required='true' class="form-control" >
                        <?php
                            echo "<option selected value=''>Selecione uma opção</option>";
                            echo "<option value='I'>Internas</option>";
                            echo "<option value='E'>Externas</option>";
                        ?>
                    </select>
                </div>
                <label>Linha</label>
                <div class="mb-3">
                    <select id="linha" name="linha" required='true' class="form-control" >
                        <?php
                            echo "<option selected value=''>Selecione uma opção</option>";
                            for($i=0; $i<count($agrupamentos);$i++){
                                echo "<option value='".$agrupamentos[$i]["NR_AGRUPAMENTO"]."'>".$agrupamentos[$i]["DESC_AGRUPAMENTO"]."</option>";
                            }
                        ?>
                    </select>
                </div>
                <label>Quantidade</label>
                <div class="mb-3">
                    <input type="number" class="form-control" id="quantidade" name="quantidade" required='true' placeholder="Quantidade" aria-label="Quantidade">
                </div>
                <div class="text-center">
                    <!-- <button type="button" class="btn bg-gradient-info w-100 mt-4 mb-0">Entrar</button> -->
                <?php 
                    echo form_button(array("class"=>"btn bg-gradient-success w-100 mt-4 mb-0", "type"=>"submit", "content"=>"Cadastrar meta"));
                    echo form_close();
                ?>
                </div>
            </div>
        </div>
    </div>
</div>