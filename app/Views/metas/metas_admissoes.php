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
                <div class="row">
                    <div class="col-lg-6 col-7">
                        <h6>Metas Admissões</h6>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" onclick="novaMeta()" aria-expanded="false"> <!-- id="dropdownTable" data-bs-toggle="dropdown" -->
                                <i class="fa fa-plus-circle text-secondary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <?php echo form_open('metas/editarMeta',array('name'=>'form_metas', 'id'=>'form_metas'));?>
                    <table class="table align-items-center justify-content-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Ano</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Quadrimestre</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Tipo</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Linha</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Quantidade</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tabela_metas" name="tabela_metas">
                            
                        </tbody>
                    </table>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('public/js/jquery-3.1.0.js');?>"></script>
<script src="<?php echo base_url('public/js/jquery.js');?>"></script>

<script>
    function novaMeta(){
        document.location.href = "metas/novaMeta";
    }
</script>

<script defer>
    $.ajax({
        url : "<?php echo site_url('metas/retornaMetasAdmissoes');?>",
        type : 'POST',
        dataType: "json",
        success : function(data){
            var result = data;
            var html_tabela = "";
            var cor_status  = "";
            var cor_quad    = "";
            for(var i = 0;i<result.length;i++){
                const dataAtual = new Date();
                const anoAtual  = dataAtual.getFullYear();
                const mesAtual  = dataAtual.getMonth();
                let quad        = "";

                if(mesAtual<=4){
                    quad = 1;
                }else if(mesAtual>4 && mesAtual<=8){
                    quad = 2;
                }else if(mesAtual>8 && mesAtual<=12){ 
                    quad = 3;
                }

                // if(result[i].ANO.toString()==anoAtual.toString()){
                //     cor_status = "font-weight-bolder";
                // }else{
                //     cor_status = "";
                // }

                if(result[i].QUADRIMESTRE.toString()==quad.toString() && result[i].ANO.toString()==anoAtual.toString()){
                    cor_quad    = "font-weight-bolder";
                    cor_status  = "font-weight-bolder";
                }else{
                    cor_quad    = "";
                    cor_status  = "";
                }

                html_tabela += '<tr>'+
                                    '<td>'+
                                        '<span class="'+cor_status+'">'+result[i].ANO+'</span>'+
                                    '</td>'+
                                    '<td class="'+cor_quad+'">'+
                                        result[i].QUADRIMESTRE+
                                    'º </td>'+
                                    '<td class="text-center">'+
                                        '<span class="text-xs text-center font-weight-bold">'+result[i].TIPO_ADMISSAO_COMPLETO+'</span>'+
                                    '</td>'+
                                    '<td class="align-middle text-center">'+
                                        '<span class="text-xs font-weight-bold">'+result[i].DESC_AGRUPAMENTO+'</span>'+
                                    '</td>'+
                                    '<td class="align-middle text-center">'+
                                        result[i].QUANTIDADE+ 
                                    '</td>'+
                                    '<td class="align-middle text-center">'+
                                        '<a onclick="editarMeta('+result[i].ID+')" href="#"><i class="fas fa-edit cursor-pointer"></i></a>'+ 
                                    '</td>'+      
                                '</tr>';
            }
            $("#tabela_metas").html(html_tabela);
        },
        error : function(data){
            alert('erro');
        }
    });
</script>