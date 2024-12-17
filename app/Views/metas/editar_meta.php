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
                <?php echo form_open('metas/atualizarMeta',array('name'=>'formAtualizaMeta', 'id'=>'formAtualizaMeta'));?>
                <label>Ano</label>
                <div class="mb-3">
                    <input type="text" class="form-control" required='true' id="ano" name="ano" placeholder="Ano" aria-label="Ano" value="<?php echo $meta["ANO"];?>">
                </div>
                <label>Quadrimestre</label>
                <div class="mb-3">
                    <select id="quadrimestre" name="quadrimestre" required='true' class="form-control" >
                        <?php
                            echo "<option value=''>Selecione uma opção</option>";
                            for($i=1; $i<=3;$i++){
                                if($i==$meta["QUADRIMESTRE"]){
                                    $c_slc = "selected";
                                }else{
                                    $c_slc = "";
                                }
                                echo "<option $c_slc value='$i'>".$i."º</option>";
                            }
                        ?>
                    </select>    
                </div>
                <label>Tipo</label>
                <div class="mb-3">
                    <select id="tipo" name="tipo" required='true' class="form-control" >
                        <?php
                            echo "<option value=''>Selecione uma opção</option>";
                            if($meta["TIPO_ADMISSAO"]=="I"){
                                echo "<option selected value='I'>Internas</option>";
                                echo "<option value='E'>Externas</option>";
                            }else{
                                echo "<option value='I'>Internas</option>";
                                echo "<option selected value='E'>Externas</option>";
                            }
                        ?>
                    </select> 
                </div>
                <label>Linha</label>
                <div class="mb-3">
                    <select id="linha" name="linha" required='true' class="form-control" >
                        <?php
                            echo "<option selected value=''>Selecione uma opção</option>";
                            for($i=0; $i<count($agrupamentos);$i++){
                                if($meta["NR_AGRUPAMENTO"]==$agrupamentos[$i]["NR_AGRUPAMENTO"]){
                                    $c_slc = "selected";
                                }else{
                                    $c_slc = "";
                                }
                                echo "<option $c_slc value='".$agrupamentos[$i]["NR_AGRUPAMENTO"]."'>".$agrupamentos[$i]["DESC_AGRUPAMENTO"]."</option>";
                            }
                        ?>
                    </select>
                </div>
                <label>Quantidade</label>
                <div class="mb-3">
                    <input type="number" class="form-control" required='true' id="quantidade" name="quantidade" placeholder="Quantidade" aria-label="Quantidade" value="<?php echo $meta["QUANTIDADE"];?>">
                </div>
                <div class="mb-3">
                    <input type="hidden" id="id_meta" name="id_meta" value="<?php echo $id_meta;?>"/>
                    <label><input style="margin-right: 5px" type="checkbox" id="excluir_meta" name="excluir_meta"/> Excluir meta</label>
                </div>
                <div class="text-center">
                    <!-- <button type="button" class="btn bg-gradient-info w-100 mt-4 mb-0">Entrar</button> -->
                <?php
                    // echo "<button class='btn bg-gradient-danger w-100 mt-4 mb-0' onclick='deletarMeta(".$id_meta.")'>Deletar meta</button>";
                    echo form_button(array("class"=>"btn bg-gradient-success w-100 mt-4 mb-0", "type"=>"submit", "content"=>"Atualizar meta"));
                    echo form_close();
                ?>
                </div>
            </div>
        </div>
    </div>
</div>
