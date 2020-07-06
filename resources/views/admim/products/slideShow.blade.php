@extends('admim.master.layout')

@section('title', 'Gestão do slide show')
@section('pageHeader', 'Gestão do slide show')


@section('content')
<?php $plugin=0; ?>
<!-- Main content -->
<section class="content container-fluid">

    @can('Cadastrar Produtos')
    <section class="content">
        <div class="col-md-6">
            <!-- DIRECT CHAT SUCCESS -->
            <div class="box box-success direct-chat direct-chat-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Adicionar produtos ao slide</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <!-- Conversations are loaded here -->
                        <div class="direct-chat-messages">
                            <form action="{{route('admim.product.slideShowOn')}}" method="POST">
                                @csrf
                                @foreach ($productsOff as $productOff)
                                <input type="checkbox" id="item-{{$productOff->id}}" name="id[]" value=" {{$productOff->id}} ">

                                <label for="item-{{$productOff->id}}">{{$productOff->name}}</label> <br> <br>
                                @endforeach

                                <button type="submit" class="btn btn-success btn-flat">Send</button>
                            </form>
                        </div>
                        <!--/.direct-chat-messages-->
                    </div>
                    <!-- /.box-body -->
                </div>
                <!--/.direct-chat -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
                <!-- DIRECT CHAT SUCCESS -->
                <div class="box box-success direct-chat direct-chat-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Retirar produtos do slide</h3>

                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                            <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Contacts" data-widget="chat-pane-toggle">
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Conversations are loaded here -->
                            <div class="direct-chat-messages">
                                <form action="{{route('admim.product.slideShowOff')}}" method="POST">
                                    @csrf
                                    @foreach ($productsOn as $productOn)
                                    <input type="checkbox" id="item-{{$productOn->id}}" name="id[]" value=" {{$productOn->id}} ">

                                    <label for="item-{{$productOn->id}}">{{$productOn->name}}</label> <br> <br>
                                    @endforeach

                                    <button type="submit" class="btn btn-success btn-flat">Send</button>
                                </form>
                            </div>
                            <!--/.direct-chat-messages-->
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!--/.direct-chat -->
                </div>
                <!-- /.col -->

        </section>
        @endcan

    </section>
    <!-- /.content -->
    @endsection
