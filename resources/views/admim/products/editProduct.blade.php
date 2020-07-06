
@extends('admim.master.layout')

@section('title', 'Gestão de Produtos')
@section('pageHeader', "Actualizar Produtos")


@section('content')
<?php $plugin=0; ?>
<!-- Main content -->
<section class="content container-fluid">
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Actualizar {{$product->name}}</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->

                    @if ($errors->any())
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                        @endforeach
                    </ul>
                    @endif

                    <form action="{{route('admim.products.update', $product->id)}}" method="POST" enctype="multipart/form-data" role="form">
                        @method('PUT')
                        @csrf
                        <div class="box-body">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nome</label>
                                <input name="name" type="text" class="form-control" id="exampleInputEmail1" placeholder="Nome do produto" value="{{$product->name}}">
                            </div>

                            <div class="form-group">
                                <label>Subcategoria</label>
                                <select name="subcategorie" id="subcategorie" class="form-control select2" style="width: 100%;">
                                    @foreach ($subcategories as $subcategorie)
                                        @if ($subcategorie->id == $product->subcategorie)
                                            <option selected value="{{$subcategorie->id}}">{{$subcategorie->name}}</option>
                                        @else
                                            <option value="{{$subcategorie->id}}">{{$subcategorie->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Marca</label>
                                <select name="brand" id="brand" class="form-control select2" style="width: 100%;">
                                    @foreach ($brands as $brand)
                                        @if ($brand->id == $product->brand)
                                            <option selected value="{{$brand->id}}">{{$brand->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- <input name="new_price" type="hidden" value="{{$product->new_price}}"> --}}
                            <div class="form-group">
                                <label for="exampleInputPassword1">Preço</label>
                                <input name="old_price" type="number" class="form-control" id="exampleInputPassword1" placeholder="Preço do produto" value="{{$product->old_price}}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Desconto</label>
                                <input name="discount" type="number" class="form-control" id="exampleInputPassword1" placeholder="Preço do produto" value="{{$product->discount}}">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Quantidade</label>
                                <input name="qtd" type="number" class="form-control" id="exampleInputPassword1" placeholder="Quantidade em stock" value="{{$product->qtd}}">
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-default collapsed-box">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Descrição</h3>
                                            <!-- tools box -->
                                            <div class="pull-right box-tools">
                                                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip"
                                                title="Expandir">
                                                <i class="fa fa-plus"></i></button>
                                            </div>
                                            <!-- /. tools -->
                                        </div>
                                        <!-- /.box-header -->
                                        <div class="box-body pad">
                                            <textarea name="description"  class="textarea" placeholder="Descrição do produto"
                                            style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">{{$product->description}}</textarea>
                                        </div>
                                    </div>
                                    <!-- /.box -->
                                </div>
                                <!-- /.col -->
                            </div>

                            <div class="form-group">
                                <label for="exampleInputFile">Imagem do produto</label>
                                <input name="image" type="file" id="exampleInputFile">
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control select2" style="width: 100%;">
                                    <option selected="selected">{{$product->status}}</option>
                                    <option>@if("{$product->status}" == "Offline") Online @endif @if("{$product->status}" == "Online")Offline @endif</option>
                                </select>
                            </div>

                        </div>
                        <!-- /.box-body -->

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success">Actualizar</button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>

</section>
<!-- /.content -->
@endsection
