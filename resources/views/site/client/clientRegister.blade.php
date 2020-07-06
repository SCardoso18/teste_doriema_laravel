<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Doriema Lda | Registro</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset('admim/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('admim/bower_components/font-awesome/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset('admim/bower_components/Ionicons/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('site/css/login_register/register.css')}}">

  {{-- <link rel="stylesheet" href="{{asset('admim/dist/css/AdminLTE.min.css')}}"> --}}

  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('admim/plugins/iCheck/square/blue.css')}}">

  <!-- Select2 -->
  <link rel="stylesheet" href="{{asset('admim/bower_components/select2/dist/css/select2.min.css')}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body>
    <!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->
	<div class="error-pagewrap">
		<div class="error-page-int">
			<div class="text-center custom-login">
				<a href="{{route('products.showHome')}}" class="logo">
                    <img src="{{asset('site/img/logo.png')}}" alt="" style="width: 250px; heigth: 150px">
                </a>
			</div>
			<div class="content-error">
				<div class="hpanel">
                    <div class="panel-body">
                        <p class="login-box-msg">
                            @if ($errors->all())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger" role="alert">
                                        {{$error}}
                                    </div>
                                @endforeach
                            @else
                                <p style="text-align: center; font-size: 14px; font-weight: bold">Terminar cadastro do seu perfil para finalizar sua encomenda.</p>
                            @endif
                        </p>
                        <form action="{{route('client.updateRegister')}}" method="POST" id="loginForm">
                            @csrf
                            <div class="row">

                                <div class="form-group col-lg-12">
                                    <label>Telefone 1 </label><i style="color: red;">*</i>
                                    <input name="telephone" type="text" class="form-control" value="{{old('telephone')}}" >
                                </div>

                                <div class="form-group col-lg-12">
                                    <label>Telefone 2</label>
                                    <input name="telephone2" type="text" class="form-control" value="{{old('telephone2')}}" >
                                </div>

                                <div class="form-group col-lg-12">
                                    <label>Província</label><i style="color: red;">*</i>
                                    <select name="province" id="province" class="form-control select2" style="width: 100%;">
                                        <option value="">Selecione uma Província</option>
                                        @foreach ($provinces as $province)
                                            <option value="{{$province->id}}">{{$province->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label>Município</label><i style="color: red;">*</i>
                                    <select name="district" id="district" class="form-control select2" style="width: 100%;">
                                    </select>
                                </div>

                                <div class="form-group col-lg-12">
                                    <label>Bairro</label><i style="color: red;">*</i>
                                    <input name="neighborhood" class="form-control" value="{{old('neighborhood')}}">
                                </div>

                                <div class="form-group col-lg-12">
                                    <label>Rua</label><i style="color: red;">*</i>
                                    <input name="street" type="text" class="form-control" value="{{old('street')}}" >
                                </div>

                                <div class="form-group col-lg-12">
                                    <label>Número casa</label>
                                    <input name="house_number" type="number" class="form-control" value="{{old('house_number')}}">
                                </div>

                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success loginbtn">Finalizar</button>
                                <button class="btn btn-default">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
			</div>
			<div class="text-center login-footer">
				<p>Copyright © {{date('Y')}} . Todos direitos reservados.</p>
			</div>
		</div>
    </div>
    </body>
</html>
<script src="{{asset('site/js/jquery.min.js')}}"></script>
<script src="{{asset('site/js/province_district.js')}}"></script>

<!-- Select2 -->
<script src="{{asset('admim/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2()
    })
</script>
