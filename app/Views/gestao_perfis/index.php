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
                        <h6>Perfis</h6>
                    </div>
                    <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" onclick="novoPerfil()" aria-expanded="false"> <!-- id="dropdownTable" data-bs-toggle="dropdown" -->
                                <i class="fa fa-plus-circle text-secondary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <?php echo form_open('perfis/editarPerfil',array('name'=>'form_perfis', 'id'=>'form_perfis'));?>
                    <table class="table align-items-center justify-content-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tabela_perfis" name="tabela_perfis">
                            
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
    function novoPerfil(){
        document.location.href = "perfis/novoPerfil";
    }
</script>

<script defer>
    $.ajax({
        url : "<?php echo site_url('perfis/retornaPerfis')?>",
        type : 'POST',
        dataType: "json",
        success : function(data){
            var result = data;
            var html_tabela = "";
            var cor_status= "";
            let status_perfil = "";
            for(var i = 0;i<result.length;i++){
            if(result[i].ATIVO==1){
                cor_status = "bg-success";
                status_perfil = "Ativo";
            }else{
                cor_status = "bg-danger";
                status_perfil = "Inativo";
            }
            html_tabela += '<tr>'+
                                '<td>'+
                                    '<div class="d-flex px-2">'+
                                        result[i].NOME_TIPO_PERFIL+
                                    '</div>'+
                                '</td>'+
                               
                                '<td>'+
                                    '<span class="'+cor_status+' text-xs text-center font-weight-bold">'+status_perfil+'</span>'+
                                '</td>'+
                                
                                
                                '<td class="align-middle text-center">'+
                                    '<a onclick="editarPerfil('+result[i].ID+')" href="#"><i class="fas fa-edit cursor-pointer"></i></a>'+ 
                                '</td>'+      
                            '</tr>';
            }
            $("#tabela_perfis").html(html_tabela);
        },
        error : function(data){
            alert('erro');
        }
    });
</script>