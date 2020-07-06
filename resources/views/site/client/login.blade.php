<div class="modal modal-success fade" id="modal-client-login">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <!-- Main content -->
                    <section class="content">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-12">
                                <!-- general form elements -->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 style="text-align: center" class="box-title">Precisa iniciar sessão para adicionar produtos ao carrinho.</h3>
                                    </div> <br>
                                    <!-- /.box-header -->

                                    <p class="login-box-msg">
                                        @if ($errors->all())
                                            @foreach ($errors->all() as $error)
                                                <div class="alert alert-danger" role="alert">
                                                    {{$error}}
                                                </div>
                                            @endforeach
                                        @else
                                            Coloque seus dados para iniciar sessão
                                        @endif
                                    </p>
                                    <!-- form start -->
                                    <form action="{{route('client.login')}}" method="POST">
                                        @csrf

                                      <div class="form-group has-feedback">
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Email" value="{{old('email')}}">
                                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                      </div>

                                      <div class="form-group has-feedback">
                                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                                        <p id="capslock" style="margin-left:2px; color:red; padding-top:4px; display: none;">CapsLock activo</p>
                                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                      </div>

                                      <div class="row">
                                        <!-- /.col -->
                                        <div class="col-xs-4">
                                          <button type="submit" class="btn btn-success btn-block btn-flat">Entrar</button>
                                        </div> <br>
                                        <!-- /.col -->
                                      </div>
                                    </form>

                                    <!-- /.col -->
                                    <br> <br>
                                    <a href="#" data-toggle="modal" data-target="#modal-client-register">
                                    <div class="col-4">
                                        <button class="btn btn-success btn-block btn-flat">Não tem uma conta! Clique aqui.</button>
                                    </div>
                                    </a>
                                      <!-- /.col -->
                                </div>
                                <!-- /.box -->
                            </div>
                        </div>
                    </section>
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Fechar</button>
                </div> --}}
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->

@push('loginScript')
    <script>
        var password = document.getElementById('password');
        var capsLock = document.getElementById('capslock');

        password.addEventListener("keyup", function(event){
            if(event.getModifierState('CapsLock'))
            {
                capsLock.style.display = "block";
            }
            else
            {
                capsLock.style.display = "none";
            }
        });
    </script>
@endpush