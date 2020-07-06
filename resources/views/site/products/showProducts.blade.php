@extends('site.master.layout')

@section('title', 'Produtos')


@section('content')
@include('site.master.includes.breadcrumb', ['titleBreadcrumb' => "Produtos"])
@php $count = 1; @endphp

<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <!-- STORE -->
            <div id="store" class="col-md-9">
                <!-- store products -->
                <div class="row">
                    @foreach ($products as $product)
                    <!-- product -->
                    <div class="col-md-4 col-xs-6">
                        @include('site.products.includes.productsSection')
                    </div>
                    <!-- /product -->
                    @php $count += 1; @endphp
                    @endforeach

                    @if ($count <= 1)
                        <p style="color: red; font-weight: bold; font-size: 20px; margin-left: 2%; line-height: 40px;" >
                            Não foram encontrados resultados para a sua pesquisa! Por favor, reveja os campos selecionados e tente novamente. </p>
                    @endif

                </div>
                <!-- /store products -->

                @if (isset($filters))
                    {!! $products->appends($filters)->links() !!}
                @else
                    {!! $products->links() !!}
                @endif

            </div>
            <!-- /STORE -->

            <form action="{{route('products.personalizedQuery')}}" method="POST">
                @csrf
            <!-- ASIDE -->
            <div id="aside" class="col-md-3">
                <!-- aside Widget -->
                <div class="aside">
                    <h3 class="aside-title">Categorias</h3>
                    <div class="checkbox-filter">
                        @foreach ($categories as $categorie)
                        <div class="input-checkbox">
                            <input type="checkbox" id="category-{{$categorie->id}}" name="categorie[]" value=" {{$categorie->id}} "

                                @if(isset($filters['categorie']))
                                    @foreach ($filters['categorie'] as $item)
                                        @if($item == $categorie->id)
                                            checked
                                        @endif
                                    @endforeach
                                @endif
                            >
                            <label for="category-{{$categorie->id}}">
                                <span></span>
                                {{$categorie->description}}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- /aside Widget -->

                <!-- aside Widget -->
                <div class="aside">
                    <h3 class="aside-title">Preço</h3>
                    <div class="price-filter">
                        <div id="price-slider"></div>
                        <div class="input-number price-min">
                            <input id="price-min" name="price_min" type="number"
                            @if(isset($filters['price_min']))
                                value=" {{ $filters['price_min'] }} "
                            @endif
                            >
                            <span class="qty-up">+</span>
                            <span class="qty-down">-</span>
                        </div>
                        <span>-</span>
                        <div class="input-number price-max">
                            <input id="price-max" name="price_max" type="number">
                            <span class="qty-up">+</span>
                            <span class="qty-down">-</span>
                        </div>
                    </div>
                </div>
                <!-- /aside Widget -->

                <!-- aside Widget -->
                <div class="aside">
                    <h3 class="aside-title">Marcas</h3>
                    <div class="checkbox-filter">
                        @foreach ($brands as $brand)
                        <div class="input-checkbox">
                            <input type="checkbox" id="brand-{{$brand->id}}" name="brand[]" value=" {{$brand->id}} "

                                @if(isset($filters['brand']))
                                    @foreach ($filters['brand'] as $item)
                                        @if($item == $brand->id)
                                            checked
                                        @endif
                                    @endforeach
                                @endif
                            >
                            <label for="brand-{{$brand->id}}">
                                <span></span>
                                {{$brand->name}}
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                <!-- /aside Widget -->


                <div class="search-categories">
                    <button type="submit" class="btn-search-categories"><i class=""></i>Fazer pesquisa personalida</button>
                </div>
                </form>

                <!-- aside Widget -->
                <div class="aside">
                    <h3 class="aside-title">MAIS COMPRADOS</h3>
                    @foreach ($moreBought as $mBought)
                        <!-- product widget -->
                        <a href="{{route('product.show', $mBought->id)}}">
                            <div class="product-widget">
                                <div class="product-img">
                                    <img src="{{url("storage/{$mBought->image}")}} " alt="{{$mBought->name}}">
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
                            </div> <br>
                        </a>
                        <!-- product widget -->
                    @endforeach
                </div>
                <!-- /aside Widget -->
            </div>
            <!-- /ASIDE -->

        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->
@endsection
