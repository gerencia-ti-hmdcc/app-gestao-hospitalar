<footer class="footer pt-3  ">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
                <div class="copyright text-center text-sm text-muted text-lg-start">
                    <small>© <script>
                        document.write(new Date().getFullYear())
                    </script>, desenvolvido pela <a href="https://www.hmdcc.com.br" class="font-weight-bold" target="_blank"> Gerência de TI HMDCC</a>.</small>
                    <div style="float: right;text-align:right">
                        <small> - Usuário logado: <?php echo " <b>".explode(' ',$_SESSION["usuario_logado"]["NOME"])[0]."</b>";?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>