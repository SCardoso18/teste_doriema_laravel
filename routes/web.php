<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Mail\sendEmailAdmin;
use App\Mail\SendEmailCompra;
use App\Mail\SendEmailNewClient;
use App\Models\Client;
use App\Models\User;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Auth;

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');




/*-------------------------------------------*/
/*              SITES ROUTES                 */
/*-------------------------------------------*/
Route::any('produtos/pesquisa', 'Site\ProductController@personalizedQuery')
->name('products.personalizedQuery');

Route::any('produtos/categoria/pesquisa', 'Site\CategorieProductController@personalizedQueryCategorie')
->name('products.categorie.personalizedQuery');

Route::any('produtos/subcategoria/pesquisa', 'Site\SubCategorieProductController@personalizedQuerysubcategorie')
->name('products.subcategorie.personalizedQuery');

    Route::group(['middleware' => 'stats'], function ()
    {
        Route::get('/', 'Site\HomeController@showHome')->name('products.showHome');

        Route::get('produtos', 'Site\ProductController@index')->name('products.index');
        Route::get('produtos/{id}', 'Site\ProductController@show')->name('product.show');

        Route::get('produtos/categoria/{id}', 'Site\CategorieProductController@show')->name('categorie.show');

        Route::get('produtos/subcategoria/{id}', 'Site\SubCategorieProductController@show')->name('subcategorie.show');

        Route::get('servicos', 'Site\ServiceController@index')->name('services.index');
        Route::get('servicos/{id}', 'Site\ServiceController@show')->name('service.show');
    });

    Route::get('/stats-today', 'Admim\StatisticsController@visitsToday')->name('stats.visitsToday');
    Route::get('/stats-month', 'Admim\StatisticsController@visitsInMonth');
    Route::get('/stats-year', 'Admim\StatisticsController@visitsInYear');
    Route::get('/stats/{column}/{date?}/{limit?}', 'Admim\StatisticsController@columnStats');

    Route::get('cliente/registro', 'Site\ClientLoginController@showRegister')->name('client.showRegister');
    Route::post('cliente/terminar_registro', 'Site\ClientLoginController@register')->name('client.register');
    Route::post('cliente/actualizar', 'Site\ClientLoginController@updateRegister')->name('client.updateRegister');


    //Pegar Municípios relacionados a uma província
    Route::get('cliente/municipios/{id}', 'Site\ClientLoginController@getDistricts');

    // //Pegar Marcas relacionadas a uma sub-categoria EDIT
    // Route::get('administracao/marcas/{id}', 'Admim\ProductController@getBrandsAdd');

    // //Pegar Marcas relacionadas a uma sub-categoria ADD
    // Route::get('administracao/produtos/{outro}/marcas/{id}', 'Admim\ProductController@getBrandsEdit');

    Route::get('cliente/login', 'Site\ClientLoginController@showLogin')->name('client.showLogin');
    Route::post('cliente/login', 'Site\ClientLoginController@login')->name('client.login');
    Route::get('cliente/logout', 'Site\ClientLoginController@logout')->name('client.logout');


    Route::get('/carrinho', 'Site\ShopCartController@index')->name('shopCart.index')->middleware('auth:client');

    Route::get('/carrinho/adicionar', function(){
        return redirect()->back();
    });

    Route::post('carrinho/adicionar', 'Site\ShopCartController@store')->name('shopCart.store');
    Route::post('carrinho/actualizarQtd', 'Site\ShopCartController@updateQtd')->name('shopCart.updateQtd');

    Route::post('carrinho/cancelar', 'Site\ShopCartController@cancel')->name('shopCart.cancel');
    Route::delete('carrinho/remover', 'Site\ShopCartController@destroy')->name('shopCart.destroy');

    Route::get('carrinho/adicionar/qtd/{productid}/{color}/{status}', 'Site\ShopCartController@upQtd')->name('shopCart.upQtd');
    Route::get('carrinho/diminuir/qtd/{productid}/{color}/{status}', 'Site\ShopCartController@downQtd')->name('shopCart.downQtd');


    Route::get('/carrinho/concluir', function(){
        return redirect()->back();
    });
    Route::post('carrinho/concluir', 'Site\ShopCartController@toEnd')->name('shopCart.toEnd');

    Route::get('carrinho/compras', 'Site\ShopCartController@purchases')->name('shopCart.purchases');
    Route::get('carrinho/checkout', 'Site\ShopCartController@checkout')->name('shopCart.checkout');

    //Pegar Municípios relacionados a uma província
    Route::get('carrinho/municipios/{id}', 'Site\ClientLoginController@getDistricts');


    /*-----------------------------EMAIL DE EFECTIVAÇÃO DE COMPRA------------------------------------------------------------------------*/

    Route::get('sendEmail/{idRequest}', function($idRequest) {

        // $user = new stdClass();
        $user = Client::find(Auth::guard('client')->id());

        $admin = new stdClass();
        $admin->name = 'Doriema Online';
        $admin->email = '1000018918@ucan.edu';

        Mail::send(new sendEmailAdmin($admin, $idRequest)); // Envio de email para o Admin (app/Mail/SendEmailAdmin)
        Mail::send(new SendEmailCompra($user, $idRequest));// Envio de email para o Cliente (app/Mail/SendEmailCompra)

        return redirect()->route('shopCart.purchases');

        // return (new SendEmailCompra($user, $idRequest));


    })->name('sendEmail.Compra');

    /*-----------------------------------------------------------------------------------------------------------------------------------*/

    /*-----------------------------EMAIL PARA NOVOS CLIENTES------------------------------------------------------------------------*/

    //Envio de emails para novos clientes
    Route::get('emailNewClient', function() {
        $client = Client::find(Auth::guard('client')->id());

        Mail::send(new SendEmailNewClient($client));


        return redirect()->route('products.showHome');

    })->name('sendEmail.newClient');

    /*-----------------------------------------------------------------------------------------------------------------------------------*/

    /*-----------------------------NEWSLLETER------------------------------------------------------------------------*/
    Route::post('newslletter', 'Site\NewsletterController@store')
        ->name('newslleter.store')->middleware('auth');;


    // Route::get('/', 'Site\AutoCompleteController@index');

   Route::post('/search/site', 'Site\AutoCompleteController@fetch')->name('site.search.product');
   Route::any('/pesquisar/produto', 'Site\AutoCompleteController@search')->name('site.search');

    /*-----------------------------PDF REQUESTS REPORTS------------------------------------------------------------------------*/
        Route::get('administracao/pedido/factura/{request_id}/{total_request}', 'Admim\ReportsRequestsController@clientPF')
        ->name('admim.Reportsrequest.clientPF')->middleware('auth');
    /*-----------------------------------------------------------------------------------------------------------------------------*/


/*---------------------------------------------*/



/*----------------------------------------------------------------------------------------------------*/
/*                                       ADMIM ROUTES                                                */
/*---------------------------------------------------------------------------------------------------*/

    Route::get('administracao', 'Admim\AccessController@home')->name('admim');

    Route::get('administracao/login', 'Admim\AccessController@showLogin')->name('admim.showLogin');

    Route::post('administracao/login', 'Admim\AccessController@login')->name('admim.login');

    Route::get('administracao/logout', 'Admim\AccessController@logout')->name('admim.logout');

    /*----------------------------------------USERS-----------------------------------------------------------*/
        //Create
        Route::post('administracao/usuarios', 'Admim\UserController@store')
        ->name('admim.users.store')->middleware('auth');

        //Read
        Route::get('administracao/usuarios', 'Admim\UserController@index')
        ->name('admim.users.index')->middleware('auth');

        Route::get('administracao/usuarios/{id}', 'Admim\UserController@show')
        ->name('admim.users.show')->middleware('auth');

        //Update
        Route::put('administracao/usuarios/{id}', 'Admim\UserController@update')
        ->name('admim.users.update')->middleware('auth');

        Route::get('administracao/usuarios/{id}/edit', 'Admim\UserController@edit')
        ->name('admim.users.edit')->middleware('auth');

        //Delete
        Route::get('administracao/usuarios/{id}/remove', 'Admim\UserController@destroy')
        ->name('admim.users.destroy')->middleware('auth');

        /*----------------------------------------ROLE_USER-----------------------------------------------------------*/
            //Create
            Route::post('administracao/funcoes_usuarios', 'Admim\RoleUserController@store')
            ->name('admim.rolesUsers.store')->middleware('auth');

            //Delete
            Route::get('administracao/funcoes_usuarios/{id}/remove', 'Admim\RoleUserController@destroy')
            ->name('admim.rolesUsers.destroy')->middleware('auth');
        /*---------------------------------------------------------------------------------------------------------*/

        /*----------------------------------------ROLES-----------------------------------------------------------*/
            //Create
            Route::post('administracao/funcoes', 'Admim\RoleController@store')
            ->name('admim.roles.store')->middleware('auth');


            //Read
            Route::get('administracao/funcoes', 'Admim\RoleController@index')
            ->name('admim.roles.index')->middleware('auth');

            Route::get('administracao/funcoes/{id}', 'Admim\RoleController@show')
            ->name('admim.roles.show')->middleware('auth');

            //Update
            Route::put('administracao/funcoes/{id}', 'Admim\RoleController@update')
            ->name('admim.roles.update')->middleware('auth');

            Route::get('administracao/funcoes/{id}/edit', 'Admim\RoleController@edit')
            ->name('admim.roles.edit')->middleware('auth');

            //Delete
            Route::get('administracao/funcoes/{id}/remove', 'Admim\RoleController@destroy')
            ->name('admim.roles.destroy')->middleware('auth');
        /*---------------------------------------------------------------------------------------------------------*/

        /*----------------------------------------PERMISSION_ROLE-----------------------------------------------------------*/
            //Create
            Route::post('administracao/permissao_funcao', 'Admim\PermissionRoleController@store')
            ->name('admim.permissionRole.store')->middleware('auth');

            //Delete
            Route::get('administracao/permissao_funcao/{id}/remove', 'Admim\PermissionRoleController@destroy')
            ->name('admim.permissionRole.destroy')->middleware('auth');
        /*---------------------------------------------------------------------------------------------------------*/

    /*---------------------------------------------------------------------------------------------------------*/



    /*----------------------------------------REQUESTS-----------------------------------------------------------*/
        //Create
        Route::post('administracao/pedidos', 'Admim\RequestController@store')
        ->name('admim.request.store')->middleware('auth');

        //Read
        Route::get('administracao/novos_pedidos', 'Admim\RequestController@news')
        ->name('admim.request.news')->middleware('auth');

        Route::get('administracao/pedidos_cancelados', 'Admim\RequestController@canceled')
        ->name('admim.request.canceled')->middleware('auth');

        Route::get('administracao/pedidos_pagos', 'Admim\RequestController@pay')
        ->name('admim.request.pay')->middleware('auth');

        Route::get('administracao/pedidos_entregues', 'Admim\RequestController@delivery')
        ->name('admim.request.delivery')->middleware('auth');

        Route::get('administracao/pedido/{id}', 'Admim\RequestController@show')
        ->name('admim.request.show')->middleware('auth');

        Route::get('administracao/relatorios', 'Admim\RequestController@reports')
        ->name('admim.request.reports')->middleware('auth');

        //Update
        Route::get('administracao/actualizar/pedido/{id}', 'Admim\RequestController@update')
        ->name('admim.request.update')->middleware('auth');

        Route::get('administracao/confirmarentrega/pedido/{id}', 'Admim\RequestController@confirmDelivery')
        ->name('admim.request.confirmDelivery')->middleware('auth');

        //Delete
        Route::get('administracao/pedidos/{id}/remove', 'Admim\RequestController@destroy')
        ->name('admim.request.destroy')->middleware('auth');

        /*-----------------------------REQUESTS REPORTS-----------------------------------------------------------*/

            /*-----------------------------EXCEL REQUESTS REPORTS------------------------------------------------------------------------*/
                Route::get('administracao/pedidos/relatorios/todos/excel', 'Admim\ReportsRequestsController@allRequestsExcel')
                ->name('admim.Reportsrequest.allRequestsExcel')->middleware('auth');

                Route::get('administracao/pedidos/relatorios/encomendados/excel', 'Admim\ReportsRequestsController@orderedRequestsExcel')
                ->name('admim.Reportsrequest.orderedRequestsExcel')->middleware('auth');

                Route::get('administracao/pedidos/relatorios/entregues/excel', 'Admim\ReportsRequestsController@deliveryRequestsExcel')
                ->name('admim.Reportsrequest.deliveryRequestsExcel')->middleware('auth');

                Route::get('administracao/pedidos/relatorios/cancelados/excel', 'Admim\ReportsRequestsController@canceledRequestsExcel')
                ->name('admim.Reportsrequest.canceledRequestsExcel')->middleware('auth');
            /*-------------------------------------------------------------------------------------------------------------------------*/

            /*-----------------------------PDF REQUESTS REPORTS------------------------------------------------------------------------*/
                Route::get('administracao/pedidos/relatorios/todos/PDF', 'Admim\ReportsRequestsController@allRequestsPDF')
                ->name('admim.Reportsrequest.allRequestsPDF')->middleware('auth');

                Route::get('administracao/pedidos/relatorios/encomendados/PDF', 'Admim\ReportsRequestsController@orderedRequestsPDF')
                ->name('admim.Reportsrequest.orderedRequestsPDF')->middleware('auth');

                Route::get('administracao/pedidos/relatorios/entregues/PDF', 'Admim\ReportsRequestsController@deliveryRequestsPDF')
                ->name('admim.Reportsrequest.deliveryRequestsPDF')->middleware('auth');

                Route::get('administracao/pedidos/relatorios/cancelados/PDF', 'Admim\ReportsRequestsController@canceledRequestsPDF')
                ->name('admim.Reportsrequest.canceledRequestsPDF')->middleware('auth');
            /*-------------------------------------------------------------------------------------------------------------------------*/

            /*-----------------------------PDF REQUESTS REPORTS------------------------------------------------------------------------*/
                Route::post('administracao/pedidos/relatorios/personalizados', 'Admim\ReportsRequestsController@personalizedRequests')
                ->name('admim.Reportsrequest.personalizedRequests')->middleware('auth');
            /*-------------------------------------------------------------------------------------------------------------------------*/

            /*-----------------------------REQUESTPRODUCTS REPORTS------------------------------------------------------------------------*/
                Route::post('administracao/pedidoindividual/relatorio', 'Admim\ReportsRequestsController@requestProducts')
                ->name('admim.ReportsRequestProducts.requestProducts')->middleware('auth');
            /*-------------------------------------------------------------------------------------------------------------------------*/



        /*----------------------------------------------------------------------------------------------------------------------------*/



    /*---------------------------------------------------------------------------------------------------------*/


    /*-----------------------------PRODUCTS-----------------------------------------------------------*/
        //Create
        Route::get('administracao/produtos/create', 'Admim\ProductController@create')
        ->name('admim.products.create')->middleware('auth');;

        Route::post('administracao/produtos', 'Admim\ProductController@store')
        ->name('admim.products.store')->middleware('auth');;

        //Read
        Route::get('administracao/produtos', 'Admim\ProductController@index')
        ->name('admim.products.index')->middleware('auth');

        Route::get('administracao/produtos/{id}', 'Admim\ProductController@show')
        ->name('admim.products.show')->middleware('auth');;

        //Update
        Route::put('administracao/produtos/{id}', 'Admim\ProductController@update')
        ->name('admim.products.update');

        Route::get('administracao/produtos/{id}/edit', 'Admim\ProductController@edit')
        ->name('admim.products.edit');

        //Delete
        Route::get('administracao/produtos/{id}/remove', 'Admim\ProductController@destroy')
        ->name('admim.products.destroy');

        //Colocar produto online
        Route::get('administracao/produtos/{id}/online', 'Admim\ProductController@online')
        ->name('admim.product.online');

        Route::get('administracao/produtos/{id}/offline', 'Admim\ProductController@offline')
        ->name('admim.product.offline');

        /*-----------------------------DETAILS PRODUCTS-----------------------------------------------------------*/
            //Create
            Route::post('administracao/produtos/{id}/adicionardetalhes', 'Admim\DetailController@store')
            ->name('admim.products.addDetail.store');

            //DELETE
            Route::get('administracao/produtos/{image}/removerdetalhes', 'Admim\DetailController@destroy')
            ->name('admim.products.addDetail.destroy');
        /*---------------------------------------------------------------------------------------------------------*/




        /*-----------------------------EXTRA IMAGES-----------------------------------------------------------*/
            //Create
            Route::post('administracao/produtos/{id}/adicionarimagem', 'Admim\ExtraImagesController@store')
            ->name('admim.products.addImage.store');

            //DELETE
            Route::get('administracao/produtos/{image}/removerimagem', 'Admim\ExtraImagesController@destroy')
            ->name('admim.products.addImage.destroy');
        /*---------------------------------------------------------------------------------------------------------*/

        /*-----------------------------PRODUCTS COLOR-----------------------------------------------------------*/
            //Create
            Route::post('administracao/corproduto', 'Admim\ProductColorController@store')
            ->name('admim.productColors.store');

            //Delete
            Route::get('administracao/corproduto/{id}/remove', 'Admim\ProductColorController@destroy')
            ->name('admim.productColors.destroy');
        /*---------------------------------------------------------------------------------------------------------*/


        /*-----------------------------CATEGORIES PRODUCTS-----------------------------------------------------------*/
            //Create
            Route::post('administracao/categoriasprodutos', 'Admim\ProductCategorieController@store')
            ->name('admim.productCategories.store');

            //Read
            Route::get('administracao/categoriasprodutos', 'Admim\ProductCategorieController@index')
            ->name('admim.productCategories.index');

            //Update
            Route::put('administracao/categoriasprodutos/{id}', 'Admim\ProductCategorieController@update')
            ->name('admim.productCategories.update');

            Route::get('administracao/categoriasprodutos/{id}/edit', 'Admim\ProductCategorieController@edit')
            ->name('admim.productCategories.edit');

            //Delete
            Route::get('administracao/categoriasprodutos/{id}/remove', 'Admim\ProductCategorieController@destroy')
            ->name('admim.productCategories.destroy');
        /*---------------------------------------------------------------------------------------------------------*/

        /*----------------------------- PRODUCTS SUB-CATEGORIES -----------------------------------------------------------*/
            //Create
            Route::post('administracao/subcategoriasprodutos', 'Admim\ProductSubCategorieController@store')
            ->name('admim.productSubCategories.store');

            //Read
            Route::get('administracao/subcategoriasprodutos', 'Admim\ProductSubCategorieController@index')
            ->name('admim.productSubCategories.index');

            //Show
            Route::get('administracao/subcategoriasprodutos/{id}', 'Admim\ProductSubCategorieController@show')
            ->name('admim.productSubCategories.show');


            //Update
            Route::put('administracao/subcategoriasprodutos/{id}', 'Admim\ProductSubCategorieController@update')
            ->name('admim.productSubCategories.update');

            Route::get('administracao/subcategoriasprodutos/{id}/edit', 'Admim\ProductSubCategorieController@edit')
            ->name('admim.productSubCategories.edit');

            //Delete
            Route::get('administracao/subcategoriasprodutos/{id}/remove', 'Admim\ProductSubCategorieController@destroy')
            ->name('admim.productSubCategories.destroy');
        /*---------------------------------------------------------------------------------------------------------*/


        /*----------------------------- PRODUCTS BRANDS -----------------------------------------------------------*/
            //Create
            Route::post('administracao/marcasprodutos', 'Admim\ProductBrandController@store')
            ->name('admim.productBrands.store');

            //Read
            Route::get('administracao/marcasprodutos', 'Admim\ProductBrandController@index')
            ->name('admim.productBrands.index');

            //Update
            Route::put('administracao/marcasprodutos/{id}', 'Admim\ProductBrandController@update')
            ->name('admim.productBrands.update');

            Route::get('administracao/marcasprodutos/{id}/edit', 'Admim\ProductBrandController@edit')
            ->name('admim.productBrands.edit');

            //Delete
            Route::get('administracao/marcasprodutos/{id}/remove', 'Admim\ProductBrandController@destroy')
            ->name('admim.productBrands.destroy');

            //Brand-SubCategorie
            Route::post('administracao/marcasprodutos/{id}/addsubcategoria', 'Admim\BrandSubCategorieController@store')
            ->name('admim.productsBrand.addSubCategorie.store');

            //Pegar Marcas relacionadas a uma sub-categoria EDIT
            Route::get('administracao/marcas/{id}', 'Admim\ProductController@getBrandsAdd');

            //Pegar Marcas relacionadas a uma sub-categoria ADD
            Route::get('administracao/produtos/{outro}/marcas/{id}', 'Admim\ProductController@getBrandsEdit');
        /*---------------------------------------------------------------------------------------------------------*/


        /*----------------------------- BRAND_SUBCATEGORIE -----------------------------------------------------------*/
            //Create
            Route::post('administracao/marcasubcategorias', 'Admim\BrandSubcategorieController@store')
            ->name('admim.brandSubcategorie.store');

            //Delete
            Route::get('administracao/marcasubcategorias/{id}/remove', 'Admim\BrandSubcategorieController@destroy')
            ->name('admim.brandSubcategorie.destroy');
        /*---------------------------------------------------------------------------------------------------------*/


        /*-----------------------------Exchange--------------------------------------------------------------------*/
            Route::put('administracao/exchange', 'Admim\ExchangeController@update')
            ->name('admim.exchange.update');
        /*---------------------------------------------------------------------------------------------------------*/

        /*-----------------------------PERCENTAGEM DE PRODUTO--------------------------------------------------------------------*/
        Route::put('administracao/taxa', 'Admim\TaxaController@update')
        ->name('admim.taxa.update');
    /*---------------------------------------------------------------------------------------------------------*/


        /*-----------------------------PRODUCT SLIDE SHOW--------------------------------------------------------------------*/
            Route::get('administracao/product/slideShow', 'Admim\productController@slideShow')
            ->name('admim.product.slideShow');

            Route::post('administracao/product/slideShow/off', 'Admim\productController@slideShowOff')
            ->name('admim.product.slideShowOff');

            Route::post('administracao/product/slideShow/on', 'Admim\productController@slideShowOn')
            ->name('admim.product.slideShowOn');
        /*---------------------------------------------------------------------------------------------------------*/

    /*---------------------------------------------------------------------------------------------------------*/


    /*-----------------------------SUPPLYING-----------------------------------------------------------*/
        //Create
        Route::post('administracao/fornecedores', 'Admim\SupplyingController@store')
        ->name('admim.supplying.store')->middleware('auth');

        //Pegar Municípios relacionados a uma província
        Route::get('administracao/municipios/{id}', 'Admim\SupplyingController@getDistricts');

        //Read
        Route::get('administracao/fornecedores', 'Admim\SupplyingController@index')
        ->name('admim.supplying.index')->middleware('auth');

        Route::get('administracao/fornecedores/{id}', 'Admim\SupplyingController@show')
        ->name('admim.supplying.show');

        //Update
        Route::put('administracao/fornecedores/{id}', 'Admim\SupplyingController@update')
        ->name('admim.supplying.update');

        Route::get('administracao/fornecedores/{id}/edit', 'Admim\SupplyingController@edit')
        ->name('admim.supplying.edit');

        //Delete
        Route::get('administracao/fornecedores/{id}/remove', 'Admim\SupplyingController@destroy')
        ->name('admim.supplying.destroy');

        /*-----------------------------SUPPLYING CONTACT-----------------------------------------------------------*/

            /*---------TELEPHONE-----------------------------------------------------------------------------*/
                //Create
                Route::post('administracao/fornecedores/telefone', 'Admim\SupplyingTelephoneController@store')
                ->name('admim.supplyingTelephone.store');

                //Delete
                Route::get('administracao/fornecedores/telefone/{id}/remove', 'Admim\SupplyingTelephoneController@destroy')
                ->name('admim.supplyingTelephone.destroy');
            /*---------------------------------------------------------------------------------------------------------*/


            /*---------EMAIL--------------------------------------------------------------------------------------*/
                //Create
                Route::post('administracao/fornecedores/email', 'Admim\SupplyingEmailController@store')
                ->name('admim.supplyingEmail.store');

                //Delete
                Route::get('administracao/fornecedores/email/{id}/remove', 'Admim\SupplyingEmailController@destroy')
                ->name('admim.supplyingEmail.destroy');
            /*---------------------------------------------------------------------------------------------------------*/

        /*---------------------------------------------------------------------------------------------------------*/

        /*-----------------------------SUPPLY-----------------------------------------------------------*/
            // CRIA NOVO FORNECIMENTO BASEADO EM UM FORNCEDOR
            Route::post('administracao/fornecedoresproduto/novofornecimento', 'Admim\SupplyController@store')
            ->name('admim.supply.store');

            //Delete
            Route::get('administracao/fornecedoresproduto/novofornecimento/{id}/remove', 'Admim\SupplyController@destroy')
            ->name('admim.supply.destroy');
        /*---------------------------------------------------------------------------------------------------------*/


    /*---------------------------------------------------------------------------------------------------------*/


    /*-----------------------------SERVICES-----------------------------------------------------------*/
        //Create
        Route::post('administracao/servicos', 'Admim\ServiceController@store')
        ->name('admim.services.store');

        //Read
        Route::get('administracao/servicos', 'Admim\ServiceController@index')
        ->name('admim.services.index');

        Route::get('administracao/servicos/{id}', 'Admim\ServiceController@show')
        ->name('admim.services.show');

        //Update
        Route::get('administracao/servicos/{id}/edit', 'Admim\ServiceController@edit')
        ->name('admim.services.edit');

        Route::put('administracao/servicos/{id}', 'Admim\ServiceController@update')
        ->name('admim.services.update');

        //Delete
        Route::get('administracao/servicos/{id}/remove', 'Admim\ServiceController@destroy')
        ->name('admim.services.destroy');

        /*-----------------------------EXTRA IMAGES-----------------------------------------------------------*/
            //CREATE
            Route::post('administracao/servicos/{id}/adicionarimagem', 'Admim\AddImagesServiceController@store')
            ->name('admim.services.addImage.store');

            //DELETE
            Route::get('administracao/servicos/{image}/removerimagem', 'Admim\AddImagesServiceController@destroy')
            ->name('admim.services.addImage.destroy');
        /*---------------------------------------------------------------------------------------------------------*/

    /*---------------------------------------------------------------------------------------------------------*/


    /*-----------------------------DASHBOARDS-----------------------------------------------------------*/

        Route::get('administracao/dashboards', 'Admim\DashboardController@index')
        ->name('admim.dashboards.index');
    /*---------------------------------------------------------------------------------------------------------*/

    /*-----------------------------CAMPANHA-----------------------------------------------------------*/
        Route::get('administracao/campanha', 'Admim\PromotionController@index')
        ->name('admim.promotion.index');

        Route::post('administracao/campanha', 'Admim\PromotionController@store')
        ->name('admim.promotion.store');

        Route::get('administracao/campanha/editar', 'Admim\PromotionController@edit')
        ->name('admim.promotion.edit');

        Route::get('/administracao/campanha/editar/cancelar', function(){
            return redirect()->route('admim.promotion.index');
        })->name('admim.promotion.edit.cancel');

        Route::put('administracao/campanha/editar', 'Admim\PromotionController@update')
        ->name('admim.promotion.update');
    /*---------------------------------------------------------------------------------------------------------*/





    /*------------------------------------------- GERAR PDF -----------------------------------------------------------------*/

        Route::get('pdf', 'PDF\MakePDFController@gerar');

    /*------------------------------------------------------------------------------------------------------------------------------*/





//SOBRE NÓS ----- LADO ADMIN

Route::get('administracao/sobrenos', 'Admim\AboutUsController@index')
        ->name('admim.sobrenos.index')->middleware('auth');

Route::post('administracao/sobrenos', 'Admim\AboutUsController@store')
        ->name('admim.sobrenos.store')->middleware('auth');;


Route::get('administracao/contactos', 'Admim\ContactsController@index')
        ->name('admim.contacts.index')->middleware('auth');

Route::post('administracao/contactos', 'Admim\ContactsController@store')
        ->name('admim.contacts.store')->middleware('auth');;


Route::get('administracao/coordBancarias', 'Admim\CoordBancariasController@index')
        ->name('admim.coordenadas.index')->middleware('auth');

Route::post('administracao/coordBancarias', 'Admim\CoordBancariasController@store')
        ->name('admim.coordenadas.store')->middleware('auth');;

Route::get('administracao/coordBancarias/{id}/edit', 'Admim\CoordBancariasController@edit')
        ->name('admim.coordenadas.edit');

Route::put('administracao/coordBancarias/{id}', 'Admim\CoordBancariasController@update')
        ->name('admim.coordenadas.update');





