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
                        <h6>Usuários</h6>
                    </div>
                    <!-- <div class="col-lg-6 col-5 my-auto text-end">
                        <div class="dropdown float-lg-end pe-4">
                            <a class="cursor-pointer" onclick="novoUsuario()" aria-expanded="false">
                                <i class="fa fa-plus-circle text-secondary"></i>
                            </a>
                        </div>
                    </div> -->
                </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <?php echo form_open('logs/logUsuario',array('name'=>'form_usuarios', 'id'=>'form_usuarios'));?>
                    <table class="table align-items-center justify-content-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Nome</th>
                                <!-- <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Email</th> -->
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

<script src="<?php echo base_url("public/js/jquery-3.1.0.js");?>"></script>
<script src="<?php echo base_url("public/js/jquery.js");?>"></script>

<script>
    function logUsuario(id){
      $("#form_usuarios").append("<input type='hidden' id='usuario_escolhido' name='usuario_escolhido' value='"+id+"'/>")
      document.getElementById("form_usuarios").submit();
    }
</script>

<script defer>
    $.ajax({
        url : "<?php echo site_url('administrador/retornaUsuarios')?>",
        type : 'POST',
        dataType: "json",
        success : function(data){
            var result = data;
            var html_tabela = "";
            var cor_status= "";
            for(var i = 0;i<result.length;i++){
            if(result[i].IE_STATUS=="Ativo"){
                cor_status = "bg-success";
            }else{
                cor_status = "bg-danger";
            }
            html_tabela += '<tr>'+
                                '<td>'+
                                    '<div class="d-flex px-2">'+
                                        '<a onclick="logUsuario('+result[i].ID+')" href="#">'+result[i].NOME+'</a>'+
                                    '</div>'+
                                '</td>'+
                                // '<td>'+
                                //     result[i].EMAIL+
                                // '</td>'+
                                '<td>'+
                                    '<span class="'+cor_status+' text-xs text-center font-weight-bold"><a onclick="logUsuario('+result[i].ID+')" href="#">'+result[i].IE_STATUS+'</a></span>'+
                                '</td>'+
                                '<td class="align-middle text-center">'+
                                    '<span class="text-xs font-weight-bold"><a onclick="logUsuario('+result[i].ID+')" href="#">'+result[i].TIPO_PERFIL+'</a></span>'+
                                '</td>'+
                                '<td class="align-middle text-center">'+
                                    '<a onclick="logUsuario('+result[i].ID+')" href="#">'+result[i].ULTIMO_LOGIN+'</a>'+ 
                                '</td>'+
                                '<td class="align-middle text-center">'+
                                    '<a onclick="logUsuario('+result[i].ID+')" href="#"><i class="fa fa-history cursor-pointer"></i></a>'+ 
                                '</td>'+      
                            '</tr>';
            }
            $("#tabela_usuarios").html(html_tabela);
        },
        error : function(data){
            alert('erro');
        }
    });
</script>