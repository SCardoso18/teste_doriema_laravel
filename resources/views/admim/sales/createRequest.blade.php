<link rel="stylesheet" href="{{asset('admim/dist/css/styleRequest.css')}}">


@extends('admim.master.layout')

@section('title', 'Gestão de Pedidos')
@section('pageHeader', 'Pedidos')


@section('content')
<?php $plugin=0; ?>
<!-- Main content -->
<section class="content container-fluid">

    <section class="content">

        <div class="esquerda">
            <div class="areaPesquisa">
                <form class="frmPesquisa" action="#" method="post">
                    <input class="textPesquisa" type="search" name="" value="" placeholder="Digite o produto a pesquisar">
                    <input class="btnPesquisa" type="button" name="" value="">
                </form>
            </div>

            <div class="areaCategoria">
                <div class="btnCategoriaMeio">
                    <input class="btnCategoria" type="button" name="" value="Ocultar">
                    <input class="btnCategoria" type="button" name="" value="Todas Categorias">
                    <input class="btnCategoria" type="button" name="" value="Mercadorias">
                </div>
            </div>

            <div class="areaProdutos">
                <div class="btnProdutosMeio">
                    <h3>Artigos</h3>
                    <button class=btnProdutos type="button" name="button">Produto 1</button>
                    <button class=btnProdutos type="button" name="button">Produto 2</button>
                    <button class=btnProdutos type="button" name="button">Produto 3</button>
                    <button class=btnProdutos type="button" name="button">Produto 4</button>
                    <button class=btnProdutos type="button" name="button">Produto 5</button>
                    <button class=btnProdutos type="button" name="button">Produto 6</button>
                    <button class=btnProdutos type="button" name="button">Produto 7</button>
                    <button class=btnProdutos type="button" name="button">Produto 8</button>
                    <button class=btnProdutos type="button" name="button">Produto 9</button>
                    <button class=btnProdutos type="button" name="button">Produto 10</button>
                    <button class=btnProdutos type="button" name="button">Produto 11</button>
                    <button class=btnProdutos type="button" name="button">Produto 12</button>
                </div>
            </div>
            <div class="verMais">
                <button class="btnVermais" type="button" name="button">1</button>
                <button class="btnVermais" type="button" name="button">2</button>
                <button class="btnVermais" type="button" name="button">3</button>
            </div>
        </div>

        <div class="direita">
            <div class="mesasVertical">
                <button class=btnVertical type="button" name="button">Mesas</button> <br> <br>
                <button class=btnVertical type="button" name="button">Vendas</button> <br> <br>
                <button class=btnVertical type="button" name="button">Descontos</button> <br> <br>
                <button class=btnVertical type="button" name="button">Opções</button> <br> <br>
                <button class=btnVertical type="button" name="button">Movimentos de Caixa</button> <br> <br>
                <button class=btnVertical type="button" name="button">Consultar</button> <br><br>
            </div>

            <div class="resto">
                <div class="linhaCimaResto">
                    <button class=btnLinhaCimaResto type="button" name="button">Cliente</button>
                    <button class=btnLinhaCimaResto type="button" name="button">Operador</button>
                    <button class=btnLinhaCimaResto type="button" name="button">Factura</button>
                </div>

                <div class="artigoInformacao">
                    <label class="labelInformacao" for="">Operador:</label> <br>
                    <label class="labelInformacao" for="">Numero:</label> <br>
                    <label class="labelInformacao" for="">Consumidor Final:</label>
                </div>

                <div class="tabela">

                    <!-- DIRECT CHAT -->
                    <div class="box box-warning direct-chat direct-chat-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Lista de Produtos</h3>

                            <div class="box-tools pull-right">
                                <span data-toggle="tooltip" title="3 Produtos na lista" class="badge bg-yellow">3</span>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <!-- Conversations are loaded here -->
                            <div class="direct-chat-messages">
                                <div class="tabela-linha">
                                    <div>
                                        <div class="artigo">
                                            <b>Artigo</b>
                                        </div>

                                        <div class="quantidade">
                                            <b>QTD</b>
                                        </div>

                                        <div class="valor">
                                            <b>Valor</b>
                                        </div>

                                        {{-- <div class="opcoes">
                                            <b>Opções</b>
                                        </div> --}}
                                    </div>

                                    <div>
                                        <div class="linha-artigo">
                                            <p>TERMINAL BLA BLA BLA BLA BLA</p>
                                        </div>

                                        <div class="linha-quantidade">
                                            <p>0123456789</p>
                                        </div>

                                        <div class="linha-valor">
                                            <p>1234567890</p>
                                        </div>

                                        {{-- <div class="linha-opcoes">
                                            <p>Opções</p>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                            <!--/.direct-chat-messages-->
                        </div>
                    </div>
                    <!--/.direct-chat -->
                </div>


                <div class="totalPagar">
                    <label for="">TOTAL A PAGAR: </label>
                </div>

                <div class="linhaCimaResto">
                    <button class=btnLinhaCimaResto type="button" name="button">Cliente</button>
                    <button class=btnLinhaCimaResto type="button" name="button">Operador</button>
                    <button class=btnLinhaCimaResto type="button" name="button">Factura</button>
                </div>

            </div>

        </div>

    </section>


</section>
<!-- /.content -->
@endsection
