@extends('site.master.layout')

@section('title', 'Início')

@section('content')


@php $count = 1; @endphp

{{-- @include('site.master.includes.slideshow') --}}

@push('carousel')

<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    {{-- <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
        <li data-target="#myCarousel" data-slide-to="1"></li>
        <li data-target="#myCarousel" data-slide-to="2"></li>
    </ol> --}}

    <!-- Wrapper for slides -->
    <div class="carousel-inner">

        <div class="item active">
            <img src="{{asset('site/img/product03.png')}}" class="imageShow" alt="Chania">
            <div class="carousel-caption">
                <div class="hot-deal">
                    <h5 class="tt"><p>Na Doriema levamos o presente e o futuro até si.</p></h5>
                    <h5>Encontre os melhores produtos aqui.</h5>
                </div>
            </div>
        </div>

        @foreach ($slideProducts as $slideProduct)
        <div class="item">
            <img src=" {{url("storage/{$slideProduct->image}")}} " class="imageShow" alt="Chania">
            <div class="carousel-caption">
                <div class="hot-deal">
                    <h5 class="tt"><p>{{$slideProduct->name}}</p></h5>
                    <h5><span>{{number_format($slideProduct->new_price, 2, ',', ' ')}}</span><br>
                        @if ($slideProduct->discount > 0)
                        <del> {{number_format($slideProduct->old_price, 2, ',', ' ')}}</del></h5>
                        @endif

                        <form method="POST" action=" {{ route('shopCart.store') }} ">
                            {{ csrf_field() }}
                            @php $colorQtd = 1; @endphp
                            @php $colorProduct = 'Default'; @endphp

                            @forelse ($colors as $color)
                            @if ($color->product == $slideProduct->id)

                            @if($color->qtd >= $colorQtd)
                            @php $colorProduct = $color->name; $colorQtd = $color->qtd; @endphp
                            @endif

                            @endif
                            @empty
                            @php $colorProduct = 'Default'; @endphp
                            @endforelse

                            <input type="hidden" name="color" value=" {{$colorProduct}} ">

                            <input type="hidden" name="qtd" value="1">

                            <input type="hidden" name="id" value=" {{ $slideProduct->id }} ">

                            @if (Auth::guard('client')->check() === true)
                            <div class="add-to-cart">
                                <button type="submit" class="primary-btn cta-btn"><i class="fa fa-shopping-cart"></i> Adicionar ao Carrinho</button>
                            </div>
                            @else
                            <div class="add-to-cart">
                                <button type="reset" data-toggle="modal" data-target="#modal-client-login" class="primary-btn cta-btn"> <i class="fa fa-shopping-cart"></i> Adicionar ao Carrinho</button>
                            </div>
                            @endif
                        </form>

                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endpush

        <!-- Left and right controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="fa fa-chevron-left" id="changeSlide"></span>
            <span class="sr-only">Anterior</span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="fa fa-chevron-right" id="changeSlide"></span>
            <span class="sr-only">Seguinte</span>
        </a>
    </div>


    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">

                <!-- section title -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">DESTAQUES</h3>
                        <div class="section-nav">
                            <ul class="section-tab-nav tab-nav">
                                <li class="active"><a data-toggle="tab" href="#tab1">Computadores</a></li>
                                <li><a data-toggle="tab" href="#tab2">Impressoras</a></li>
                                <li><a data-toggle="tab" href="#tab3">Consumíveis e Papel</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /section title -->

                <!-- Products tab & slick -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="products-tabs">
                            <!-- tab -->
                            <div id="tab1" class="tab-pane active">
                                <div class="products-slick" data-nav="#slick-nav-1">
                                    @foreach ($products as $product)
                                    <!-- product -->
                                    @if ($product->categorie == 1)
                                    @include('site.products.includes.productsSection')
                                @endif
                                    <!-- /product -->
                                    @php $count += 1; @endphp
                                    @endforeach
                                </div>
                                <div id="slick-nav-1" class="products-slick-nav"></div>
                            </div>
                            <!-- /tab -->

                            <!-- tab1 -->
                            <div id="tab2" class="tab-pane fade in">
                                <div class="products-slick" data-nav="#slick-nav-2">
                                    @foreach ($products as $product)
                                    <!-- product -->
                                    @if ($product->categorie == 6)
                                        @include('site.products.includes.productsSection')
                                    @endif

                                    <!-- /product -->
                                    @php $count += 1; @endphp
                                    @endforeach
                                </div>
                                <div id="slick-nav-1" class="products-slick-nav"></div>
                            </div>
                            <!-- /tab1 -->

                            <!-- tab -->
                            <div id="tab3" class="tab-pane fade in">
                                <div class="products-slick" data-nav="#slick-nav-3">
                                    @foreach ($products as $product)
                                    <!-- product -->
                                        @if ($product->categorie == 2)
                                            @include('site.products.includes.productsSection')
                                        @endif
                                    <!-- /product -->
                                    @php $count += 1; @endphp
                                    @endforeach
                                </div>
                                <div id="slick-nav-1" class="products-slick-nav"></div>
                            </div>
                            <!-- /tab -->


                        </div>
                    </div>
                </div>
                <!-- Products tab & slick -->


            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    {{-- @if(isset($promotion))
        <!-- HOT DEAL SECTION -->
        <div id="hot-deal" class="section">
            <!-- container -->
            <div class="container">
                <!-- row -->
                <div class="row">
                    <div class="col-md-12">

                        <!-- shop -->
                        <div class="homePosts">
                            <a href="{{route('product.show', $product01->id)}}">
                            <div name="imagem" class="homePostsImagens">
                                <img src=" {{url("storage/{$product01->image}")}} " alt=" {{$product01->name}} ">
                            </div>
                            </a>
                        </div>
                        <!-- /shop -->

                        <div class="hot-deal">
                            <!-- shop -->
                            <div class="homePosts2">
                                <a href="{{route('product.show', $product02->id)}}">
                                    <div name="imagem" class="homePostsImagens">
                                    <img src=" {{url("storage/{$product02->image}")}} " alt=" {{$product02->name}} ">
                                    </div>
                                </a>
                            </div>
                            <!-- /shop -->

                            <ul class="hot-deal-countdown">
                                <li>
                                    <div>
                                        <h3>02</h3>
                                        <span>Days</span>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <h3>10</h3>
                                        <span>Hours</span>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <h3>34</h3>
                                        <span>Mins</span>
                                    </div>
                                </li>
                                <li>
                                    <div>
                                        <h3>60</h3>
                                        <span>Secs</span>
                                    </div>
                                </li>
                            </ul>

                            <h2 class="text-uppercase">Grande oportunidade</h2>
                            <p> {{$promotion->description}} </p>

                            <form method="POST" action=" {{ route('shopCart.store') }} ">
                                {{ csrf_field() }}

                                @php $colorQtd = 1; @endphp
                                @php $colorProduct = 'Default'; @endphp

                                @forelse ($colors as $color)
                                    @if ($color->product == $product->id)

                                        @if($color->qtd >= $colorQtd)
                                            @php $colorProduct = $color->name; $colorQtd = $color->qtd; @endphp
                                        @endif

                                    @endif
                                @empty
                                    @php $colorProduct = 'Default'; @endphp
                                @endforelse

                                <input type="hidden" name="color" value=" {{$colorProduct}} ">

                                <input type="hidden" name="qtd" value="1">

                                <input type="hidden" name="id" value=" {{ $product01->id }} ">

                                <input type="hidden" name="promotion" value="true">

                                @if (Auth::guard('client')->check() === true)
                                    <div class="add-to-cart">
                                        <button type="submit" class="primary-btn cta-btn"><i class="fa fa-shopping-cart"></i> Adicionar ao Carrinho</button>
                                    </div>
                                @else
                                    <div class="add-to-cart">
                                        <button type="reset" data-toggle="modal" data-target="#modal-client-login" class="primary-btn cta-btn"><i class="fa fa-shopping-cart"></i> Adicionar ao Carrinho</button>
                                    </div>
                                @endif

                            </form>

                        </div>

                    </div>


                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /HOT DEAL SECTION -->
    @endif --}}


        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/zpOULjyy-n8?rel=0" allowfullscreen></iframe>
        </div>

    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- section title -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Nossos Serviços</h3>
                    </div>
                </div>
                <!-- /section title -->

                <!-- Products tab & slick -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="products-tabs">
                            <!-- tab -->
                            <div id="tab2" class="tab-pane fade in active">
                                <div class="products-slick" data-nav="#slick-nav-2">
                                    @foreach ($servicesAll as $serviceAll)
                                    <!-- service -->
                                    <div class="col-md-4 col-xs-6">
                                        @include('site.services.includes.servicesSection')
                                    </div>
                                    <!-- /service -->
                                    @endforeach
                                </div>
                                <div id="slick-nav-2" class="products-slick-nav"></div>
                            </div>
                            <!-- /tab -->
                        </div>
                    </div>
                </div>
                <!-- /Products tab & slick -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-6 col-xs-6">
                    <div class="section-title">
                        <h4 class="title">MAIS COMPRADOS</h4>
                        <div class="section-nav">
                            <div id="slick-nav-3" class="products-slick-nav"></div>
                        </div>
                    </div>

                    <div class="products-widget-slick" data-nav="#slick-nav-3">
                        @foreach ($moreBought as $mBought)
                        <div>
                            <!-- product widget -->
                            <a href="{{route('product.show', $mBought->id)}}">
                                <div class="product-widget">
                                    <div class="product-img">
                                        <img src=" {{url("storage/{$mBought->image}")}} " alt="{{$mBought->name}}">
                                    </div>
                                    <div class="product-body">
                                        <p class="product-category">{{$mBought->brand}}</p>
                                        <h3 class="product-name">{{$mBought->name}}</h3>
                                        <h4 class="product-price">AKZ {{ number_format($mBought->new_price, 2, ',', ' ')}}
                                            @if ($mBought->discount != null)
                                                <del class="product-old-price">{{ number_format($mBought->old_price, 2, ',', ' ')}}</del>
                                            @endif
                                        </h4>
                                    </div>
                                </div>
                            </a>
                            <!-- product widget -->
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-6 col-xs-6">
                    <div class="section-title">
                        <h4 class="title">MELHORES DESCONTOS</h4>
                        <div class="section-nav">
                            <div id="slick-nav-4" class="products-slick-nav"></div>
                        </div>
                    </div>

                    <div class="products-widget-slick" data-nav="#slick-nav-4">
                        @foreach ($bestsDiscount as $bestDiscount)
                        <div>
                            <!-- product widget -->
                            <a href="{{route('product.show', $bestDiscount->id)}}">
                                <div class="product-widget">
                                    <div class="product-img">
                                        <img src="{{url("storage/{$bestDiscount->image}")}} " alt="{{$bestDiscount->name}}">
                                    </div>
                                    <div class="product-body">
                                        <p class="product-category">{{$bestDiscount->brand}}</p>
                                        <h3 class="product-name">{{$bestDiscount->name}}</h3>
                                        <h4 class="product-price">AKZ {{ number_format($bestDiscount->new_price, 2, ',', ' ')}}
                                            @if ($bestDiscount->discount != null)
                                                <del class="product-old-price">{{ number_format($bestDiscount->old_price, 2, ',', ' ')}}</del>
                                            @endif
                                        </h4>
                                    </div>
                                </div>
                            </a>
                            <!-- product widget -->
                        </div>
                        @endforeach
                    </div>

                    </div>
                </div>

                <div class="clearfix visible-sm visible-xs"></div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    @endsection
