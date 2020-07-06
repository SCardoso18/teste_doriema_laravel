<div class="modal modal-success fade" id="modal-client-register">
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
                                        <h3 style="text-align: center" class="box-title">Criar uma conta Doriema! Mais fácil seria difícil.</h3>
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
                                            Preencha os campos abaixo para criar sua conta.
                                        @endif
                                    </p>
                                    <!-- form start -->
                                    <form action="{{route('client.register')}}" method="POST" id="loginForm">
                                        @csrf
                                        <div class="row">
                                            <div class="form-group col-lg-12">
                                                <label>Nome</label><i style="color: red;">*</i>
                                                <input name="name" type="text" class="form-control" value="{{old('name')}}">
                                            </div>

                                            <div class="form-group col-lg-12">
                                                <label>Email</label><i style="color: red;">*</i>
                                                <input name="email" type="email" class="form-control" value="{{old('email')}}" >
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <label>Palavra-passe</label><i style="color: red;">*</i>
                                                <input id="password1" name="password" type="password" class="form-control">
                                                <p id="capslock1" style="margin-left:2px; color:red; padding-top:4px; display:none; ">CapsLock activo</p>
                                            </div>

                                            <div class="form-group col-lg-6">
                                                <label>Repetir palavra-passe</label><i style="color: red;">*</i>
                                                <input id="password2" name="confirm_password" type="password" class="form-control">
                                                <p id="capslock2" style="margin-left:2px; color:red; padding-top:4px; display:none; ">CapsLock activo</p>
                                            </div>
                                        </div> <br> <br>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-success btn-block btn-flat">Criar conta</button>
                                        </div>
                                    </form>
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
        var password1 = document.getElementById('password1');
        var capsLock1 = document.getElementById('capslock1');

        password1.addEventListener("keyup", function(event){
            if(event.getModifierState('CapsLock'))
            {
                capsLock1.style.display = "block";
            }
            else
            {
                capsLock1.style.display = "none";
            }
        });

        var password2 = document.getElementById('password2');
        var capsLock2 = document.getElementById('capslock2');

        password2.addEventListener("keyup", function(event){
            if(event.getModifierState('CapsLock'))
            {
                capsLock2.style.display = "block";
            }
            else
            {
                capsLock2.style.display = "none";
            }
        });
        
    </script>
@endpush