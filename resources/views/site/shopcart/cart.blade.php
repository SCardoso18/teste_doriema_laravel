@extends('site.master.layout')

@section('title', 'Início')

@section('content')
@include('site.master.includes.breadcrumb', ['titleBreadcrumb' => "Carrinho de Compras"])


@php $mostrar = 1;  @endphp
<div class="container py">

    <!-- Cart -->

    <div class="cart_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="cart_container">

                        @include('site.master.includes.msgs')



                        @forelse($requests as $request)

                        <!-- Cabeçalho -->
                        <div class="request-header">
                            <div class="request-id">Pedido Refer.: {{$request->id}}</div>
                            <div class="request-date">Criado em: {{ $request->created_at->format('d/m/Y') $request->time }} </div>
                        </div>

                        <div class="cart_items">
                            <ul class="cart_list">
                                @php
                                $total_itens = 0;
                                $total_pedido = 0;
                                $contarProductQtd = 1;
                                @endphp
                                @forelse ($request->RequestProduct as $RP)
                                <li class="cart_item clearfix">
                                    <div  title="Remover produto">
                                        <a href="#" onclick="shopCartRemoveProduct( {requestID: {{$request->id}}, productID: {{$RP->product->id}}, item: 0, color: '{{$RP->color}}', status: '{{$RP->status}}'  } )">
                                            <img src="{{url("site/img/icons/Delete_30px.png")}}" alt="Retirar">
                                        </a>
                                    </div>
                                    <div class="cart_item_image"  title="Ver produto">
                                        <a href="{{route('product.show', $RP->product->id)}}">
                                            <img src="{{url("storage/{$RP->product->image}")}}" alt="{{ $RP->product->name }}">
                                        </a>
                                    </div>
                                    <div class="cart_item_info d-flex flex-md-row flex-column justify-content-between">
                                        <div class="cart_item_name cart_info_col">
                                            <div class="cart_item_title">Producto</div>
                                            <a href="{{route('product.show', $RP->product->id)}}">
                                                <div class="cart_item_text">{{ $RP->product->name }} </div>
                                            </a>
                                        </div>

                                        <div style="display: none" class="cart_item_color cart_info_col">
                                            <div class="cart_item_title">Cor</div>
                                            <div class="cart_item_text">{{$RP->color}}</div>
                                        </div>

                                        <div class="cart_item_quantity cart_info_col">
                                            <div class="cart_item_title">Quantidade</div>

                                            <form id="form-add-product" method="POST" action=" {{route('shopCart.updateQtd')}} ">
                                                {{ csrf_field() }}
                                                <input type="hidden" name='id'>
                                                <input type="hidden" name='color'>
                                                <input type="hidden" name='status'>

                                                <div class="qty-label">
                                                    <label>
                                                        <div class="input-number price-min">

                                                            <input name="qtd" id="price-min-{{$RP->id}}" type="number" value="{{$RP->qtd}}" onkeyup="shopCartQtdProduct( {RequestProductID: {{$RP->id}} ,productID: {{$RP->product->id}},  color: '{{$RP->color}}', status: '{{$RP->status}}'} )" >

                                                            <a title="Acrescentar quantidade"
                                                            href="{{ route('shopCart.downQtd', ['productid' => $RP->product->id, 'color' => $RP->color, 'status' => $RP->status ]) }}">
                                                                <span class="qty-down">-</span>
                                                            </a>

                                                            <a title="Acrescentar quantidade"
                                                            href="{{ route('shopCart.upQtd', ['productid' => $RP->product->id, 'color' => $RP->color, 'status' => $RP->status ]) }}">
                                                                <span class="qty-up">+</span>
                                                            </a>
                                                        </div>
                                                    </label>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="cart_item_price cart_info_col">
                                            <div class="cart_item_title">Preço</div>
                                            <div class="cart_item_text">AKZ {{ number_format($RP->product->new_price, 2, ',', '.') }}</div>
                                        </div>
                                        @php
                                            $total_produto = $RP->product->new_price * $RP->qtd;
                                            $total_itens+=1;
                                            $total_pedido += $total_produto;
                                        @endphp
                                        <div class="cart_item_total cart_info_col">
                                            <div class="cart_item_title">Total</div>
                                            <div class="cart_item_text">AKZ {{ number_format($total_produto, 2, ',', '.') }}</div>
                                        </div>
                                    </div>
                                </li>
                                @empty
                                @php $mostrar = 0; @endphp

                                <div class="callout callout-info">
                                    <h4>Clique no botão comprar para adicionar produtos a este pedido.</h4>
                                </div>
                                @endforelse
                            </ul>
                        </div>

                        <!-- Order Total -->
                        <!-- <div class="order_total">
                            <div class="order_total_content text-md-right">
                                <div class="order_total_title">Contra valor:</div>
                                <div class="order_total_amount">USD {{ number_format($total_pedido/$exchange->exchange, 2, ',', '.') }}</div>

                                <div class="order_total_title">Sub Total:</div>
                                <div class="order_total_amount">AKZ {{ number_format($total_pedido, 2, ',', '.') }}</div>
                            </div>
                        </div> -->

                        <div class="request-header" style="margin-top: 1.5%; ">
                            <div class="request-id">Sub Total:  {{ number_format($total_pedido, 2, ',', '.') }} AKZ</div>
                            <div class="request-date">Contra valor: {{ number_format($total_pedido/$exchange->exchange, 2, ',', '.') }} USD </div>
                        </div>
                        @empty
                            @php $mostrar = 0; @endphp

                            <div class="callout callout-info">
                                <h4>Não possui produto no carrinho!</h4>
                            </div>
                        @endforelse

                        @if ($mostrar == 1 )
                        <div class="cart_buttons">
                            <a href="{{route('products.index')}}">
                                <button type="button" class="button cart_button_clear">Continuar comprando</button>
                            </a>

                           <a href="{{ route('admim.Reportsrequest.clientPF', ['request_id' => $request->id, 'total_request' => $total_pedido ]) }}">
                                <button type="button" class="button cart_button_clear">Factura Pro-forma</button>
                            </a>


                            <a href="{{route('shopCart.checkout')}}">
                                <button type="button" class="button cart_button_checkout">Concluir compra</button>
                            </a>
                        </div>

                        @else
                            <a href="{{route('products.index')}}">
                                <div class="cart_buttons">
                                    <button type="button" class="button cart_button_clear">Comprar</button>
                                </div>
                            </a>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<form id="form-remove-product" method="POST" action=" {{route('shopCart.destroy')}} ">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}

    <input type="hidden" name='request_id'>
    <input type="hidden" name='product_id'>
    <input type="hidden" name='item'>
    <input type="hidden" name='color'>
    <input type="hidden" name='status'>
</form>


@endsection

@push('StyleCart')
<link type="text/css" rel="stylesheet" href="{{asset('site/css/outrosite/cart_styles.css')}}"/>
<link type="text/css" rel="stylesheet" href="{{asset('site/css/outrosite/cart_responsive.css')}}"/>
<link type="text/css" rel="stylesheet" href="{{asset('site/css/outrosite/bootstrap.min.css')}}"/>
@endpush


@push('ScriptCart')
<script src="{{asset('site/js/outrosite/cart_custom.js')}}"></script>
<script src="{{asset('site/js/outrosite/bootstrap.min.js')}}"></script>
<script src="{{asset('site/js/outrosite/popper.js')}}"></script>
<script src="{{asset('site/js/shopCart.js')}}"></script>
@endpush
