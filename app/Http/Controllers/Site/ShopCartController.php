<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Request as Pedido;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ProductModel;
use App\Models\RequestProduct;
use App\Models\Exchange;
use App\Models\Client;
use App\Models\ProvinceModel;
use App\Models\DisrictModel;
use App\Models\RequestAddress;
use App\Models\ProductColorModel;
use App\Models\Color;


class ShopCartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:client');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $requests = Pedido::where([
            'status' => 'RE',
            'client_id' => Auth::guard('client')->id()
        ])->get();


        $exchange = Exchange::find(1);

        // DEFAULT PRO MENU
        $categories = DB::select(" SELECT DISTINCT product_categorie_tb.* FROM product_categorie_tb, product_subcategorie_tb, product_tb
        WHERE product_tb.subcategorie = product_subcategorie_tb.id
        AND product_subcategorie_tb.categorie = product_categorie_tb.id
        AND product_tb.status = 'online' AND product_categorie_tb.eliminado = 'no'  ");

        $subcategories = DB::select(" SELECT DISTINCT product_subcategorie_tb.* FROM product_subcategorie_tb, product_tb
        WHERE product_tb.subcategorie = product_subcategorie_tb.id
        AND product_tb.status = 'online' AND product_subcategorie_tb.eliminado = 'no' ");

        $services = DB::select(" SELECT * FROM service_tb WHERE status = 'online' ORDER BY id DESC ");

        $contacts = DB::select(" SELECT * FROM contacts_doriema ");
        //----

        // dd([
        //     $requests,
        //     $requests[0]->RequestProduct,
        //     $requests[0]->RequestProduct[0]->product
        // ]);

        return view('site.shopCart.cart', [
            'categories' => $categories,
            'subcategories' => $subcategories,
            'services' => $services,

            'requests' => $requests,
            'exchange' => $exchange,
            'contacts' => $contacts,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->middleware('VerifyCsrfToken');

        $req = Request();

        $productID = $req->input('id');
        $color = $req->input('color');
        $qtd = $req->input('qtd');

        if($qtd <= 0)
        {
            $req->session()->flash('mensagem-falha', "Lamentamos, mas $qtd não é uma quantidade válida!");
            return back();
        }

        $product = ProductModel::find($productID);

        if($product->qtd == 0)
        {
            $req->session()->flash('mensagem-falha', "Lamentamos, mas infelizmente ficamos sem stock para este produto! ");
            return back();
        }

        if($qtd > $product->qtd)
        {
            $req->session()->flash('mensagem-falha', "Lamentamos, mas não pode adicionar $qtd unidades ao carrinho, deste produto! ");
            return back();
        }

        if($color != 'Default')
        {
            $colorP = Color::where('color', $color)->first();
            $productColor = ProductColorModel::where('color', $colorP->id)->where('product', $product->id)->first();

            if($qtd > $productColor->qtd)
            {
                $req->session()->flash('mensagem-falha', "Lamentamos, mas não pode adicionar $qtd unidade(s) ao carrinho, deste produto com a cor $color. ");
                return back();
            }
        }

        if(empty($product->id))
        {
            $req->session()->flash('mensagem-falha', 'Lamentamos, mas este produto não foi encontrado na nossa loja!');
            return redirect()->route('shopCart.index');
        }

        $clientID = Auth::guard('client')->id();

        $requestID = Pedido::queryID([
            'client_id' => $clientID,
            'status' => 'RE',
        ]);

        if(empty($requestID))
        {
            $newRequest = Pedido::create([
                'client_id' => $clientID,
                'status' => 'RE',
            ]);

            $requestID = $newRequest->id;
        }

        $verification = RequestProduct::where('request_id', $requestID)->where('product_id', '=', $productID)->where('color', '=', $color)->where('status', '=', 'RE')->get();

        if( $verification->count() != 0 )
        {
            foreach($verification as $vf)
            { $vf->qtd; }

            if($vf->qtd+$qtd > $product->qtd)
            {
                $req->session()->flash('mensagem-falha', "Lamentamos, mas não pode adicionar mais $qtd unidade(s) ao carrinho, deste produto. ");
                return back();
            }

            if($color != 'Default')
            {
                if($vf->qtd+$qtd > $productColor->qtd)
                {
                    $req->session()->flash('mensagem-falha', "Lamentamos, mas não pode adicionar mais $qtd unidade(s) ao carrinho, deste produto com a cor $color. ");
                    return back();
                }
            }

            DB::select("UPDATE request_product SET qtd = $vf->qtd+{$qtd} WHERE request_id = {$requestID}
            AND product_id = {$productID} AND color = '$color' AND status = 'RE' ");

            $req->session()->flash('mensagem-sucesso', 'Quantidade actualizada com sucesso!');

            return back();
        }

        else
        {
            RequestProduct::create([
                'request_id' => $requestID,
                'product_id' => $productID,
                'value' => $product->new_price,
                'status' => 'RE',
                'qtd' => $qtd,
                'color' => $color,
            ]);

            $req->session()->flash('mensagem-sucesso', 'Produto adicionado ao carrinho com sucesso!');

            return back();
        }
    }

    public function purchases()
    {
        $clientID = Auth::guard('client')->id();

        $purchases = Pedido::where([
            'status' => 'EC',
            'client_id' => $clientID,
        ])->orderBy('created_at', 'DESC')->paginate(2);

        foreach($purchases as $purchase)
        {
            $date1 = strtotime( $purchase->created_at );

            $date2 = strtotime(date('Y/m/d'));

            $intervalo = abs( $date2 - $date1 ) / 60;

            if($intervalo >= 2880)
            {
                Pedido::where('id', '=', $purchase->id)->update(['status' => 'CA', 'canceled_for' => 'TIME']);

                $productsQtdIncrease = RequestProduct::where([
                    'request_id' => $purchase->id,
                    'status' => 'EC',
                ])->get();

                foreach($productsQtdIncrease as $productQtdIncrease)
                {
                    $product = ProductModel::find($productQtdIncrease->product_id);

                    ProductModel::where('id', $product->id)->update(['qtd' => $product->qtd + $productQtdIncrease->qtd]);
                }

                RequestProduct::where([
                    'request_id' => $purchase->id,
                    'status' => 'EC',
                ])->update(['status' => 'CA']);
            }
        }

        $canceled = Pedido::where([
            'status' => 'PA',
            'client_id' => $clientID,
        ])->orWhere('status', '=', 'EN')->orderBy('updated_at', 'DESC')->paginate(2);

       // DEFAULT PRO MENU
        $categories = DB::select(" SELECT DISTINCT product_categorie_tb.* FROM product_categorie_tb, product_subcategorie_tb, product_tb
        WHERE product_tb.subcategorie = product_subcategorie_tb.id
        AND product_subcategorie_tb.categorie = product_categorie_tb.id
        AND product_tb.status = 'online' AND product_categorie_tb.eliminado = 'no'  ");

        $subcategories = DB::select(" SELECT DISTINCT product_subcategorie_tb.* FROM product_subcategorie_tb, product_tb
        WHERE product_tb.subcategorie = product_subcategorie_tb.id
        AND product_tb.status = 'online' AND product_subcategorie_tb.eliminado = 'no' ");

        $services = DB::select(" SELECT * FROM service_tb WHERE status = 'online' ORDER BY id DESC ");

        $requests = Pedido::where([
            'status' => 'RE',
            'client_id' => Auth::guard('client')->id()
        ])->get();

        $contacts = DB::select(" SELECT * FROM contacts_doriema ");

       //----


       return view('site.shopCart.purchases', [
           'categories' => $categories,
           'subcategories' => $subcategories,
           'services' => $services,

           'purchases' => $purchases,
           'canceled' => $canceled,
           'requests' => $requests,
           'contacts' => $contacts,
       ]);
    }

    /*
    |    Acrescentar uma unidade a um determinado produto que está no carrinho
    */
    public function upQtd($productid, $colorProduct, $statusProduct)
    {
        $this->middleware('VerifyCsrfToken');

        $req = Request();

        $productID = $productid;
        $color = $colorProduct;
        $status = $statusProduct;

        $product = ProductModel::find($productID);

        if(empty($product->id))
        {
            $req->session()->flash('mensagem-falha', 'Lamentamos, mas este produto não foi encontrado na nossa loja!');
            return redirect()->route('shopCart.index');
        }

        $clientID = Auth::guard('client')->id();

        $requestID = Pedido::queryID([
            'client_id' => $clientID,
            'status' => 'RE',
        ]);

        $requestProduct = RequestProduct::where('request_id', '=', $requestID)->where('product_id', '=', $productID)
                                          ->where('color', '=', $color)->where('status', '=', 'RE')->first();

        if($product->qtd == 0)
        {
            RequestProduct::where('request_id', '=', $requestID)->where('product_id', '=', $productID)->where('status', '=', 'RE')->delete();

            $req->session()->flash('mensagem-falha', "Lamentamos, mas infelizmente ficamos sem stock para este produto!
            Sentimos muito mas ele foi deletado do seu carrinho. ");
            return back();
        }

        if($color != 'Default')
        {
            $colorP = Color::where('color', $color)->first();
            $productColor = ProductColorModel::where('color', $colorP->id)->where('product', $product->id)->first();

            // Apaga produto no pedido cuja a quantidade da cor esteja zerada
            if($productColor->qtd == 0)
            {
                $requestProduct->delete();
                $req->session()->flash('mensagem-falha', "Lamentamos, mas infelizmente ficamos sem stock para este produto com a cor $color!
                                        Sentimos muito mas ele foi deletado do seu carrinho. ");
                return back();
            }

            if($requestProduct->qtd+1 > $productColor->qtd)
            {
                $req->session()->flash('mensagem-falha', "Lamentamos, mas não pode adicionar mais uma unidade ao carrinho, deste produto com a cor $color! ");
                return back();
            }
        }

        if($color == 'Default')
        {
            if($requestProduct->qtd+1 > $product->qtd)
            {
                $req->session()->flash('mensagem-falha', "Lamentamos, mas não pode adicionar mais uma unidade ao carrinho, deste produto! ");
                return back();
            }
        }

        DB::select("UPDATE request_product SET qtd = $requestProduct->qtd+1 WHERE request_id = {$requestID}
        AND product_id = {$productID} AND color = '$color' AND status = 'RE' ");

        $req->session()->flash('mensagem-sucesso', 'Quantidade actualizada com sucesso!');
        return back();
    }

    public function downQtd($productid, $colorProduct, $statusProduct)
    {
        $this->middleware('VerifyCsrfToken');

        $req = Request();

        $productID = $productid;
        $color = $colorProduct;
        $status = $statusProduct;

        $product = ProductModel::find($productID);

        if(empty($product->id))
        {
            $req->session()->flash('mensagem-falha', 'Lamentamos, mas este produto não foi encontrado na nossa loja!');
            return back();
        }

        $clientID = Auth::guard('client')->id();

        $requestID = Pedido::queryID([
            'client_id' => $clientID,
            'status' => 'RE',
        ]);

        if($product->qtd == 0)
        {
            RequestProduct::where('request_id', '=', $requestID)->where('product_id', '=', $productID)->where('status', '=', 'RE')->delete();

            $req->session()->flash('mensagem-falha', "Lamentamos, mas infelizmente ficamos sem stock para este produto!
            Sentimos muito mas ele foi deletado do seu carrinho. ");
            return back();
        }

        $requestProduct = RequestProduct::where('request_id', '=', $requestID)->where('product_id', '=', $productID)
                                          ->where('color', '=', $color)->where('status', '=', 'RE')->first();

        if($requestProduct->qtd-1 == 0)
        {
            $qtd = $requestProduct->qtd-1;

            $req->session()->flash('mensagem-falha', "Lamentamos, mas $qtd não é uma quantidade válida!");
            return back();
        }

        if($color != 'Default')
        {
            $colorP = Color::where('color', $color)->first();
            $productColor = ProductColorModel::where('color', $colorP->id)->where('product', $product->id)->first();

            // Apaga produto no pedido cuja a quantidade da cor esteja zerada
            if($productColor->qtd == 0)
            {
                $requestProduct->delete();

                $req->session()->flash('mensagem-falha', "Lamentamos, mas infelizmente ficamos sem stock para este produto com a cor $color!
                                        Sentimos muito mas ele foi deletado do seu carrinho. ");
                return back();
            }
        }

        DB::select("UPDATE request_product SET qtd = $requestProduct->qtd-1 WHERE request_id = {$requestID}
        AND product_id = {$productID} AND color = '$color' AND status = 'RE' ");

        $req->session()->flash('mensagem-sucesso', 'Quantidade actualizada com sucesso!');
        return back();
    }

    public function updateQtd()
    {
        $this->middleware('VerifyCsrfToken');

        $req = Request();

        $productID = $req->input('id');
        $color = $req->input('color');
        $qtd = $req->input('qtd');

        if($qtd <= 0)
        {
            $req->session()->flash('mensagem-falha', "Lamentamos, mas $qtd não é uma quantidade válida! ");
            return back();
        }

        $product = ProductModel::find($productID);

        if(empty($product->id))
        {
            $req->session()->flash('mensagem-falha', 'Produto não encontrado na nossa loja!');
            return redirect()->route('shopCart.index');
        }

        $clientID = Auth::guard('client')->id();

        $requestID = Pedido::queryID([
            'client_id' => $clientID,
            'status' => 'RE',
        ]);


        if($product->qtd == 0)
        {
            RequestProduct::where('request_id', '=', $requestID)->where('product_id', '=', $productID)->where('status', '=', 'RE')->delete();

            $req->session()->flash('mensagem-falha', "Lamentamos, mas infelizmente ficamos sem stock para este produto!
            Sentimos muito mas ele foi deletado do seu carrinho. ");
            return back();
        }

        $requestProduct = RequestProduct::where('request_id', '=', $requestID)->where('product_id', '=', $productID)
                                          ->where('color', '=', $color)->where('status', '=', 'RE')->first();

        if($color != 'Default')
        {
            $colorP = Color::where('color', $color)->first();
            $productColor = ProductColorModel::where('color', $colorP->id)->where('product', $product->id)->first();

            // Apaga produto no pedido cuja a quantidade da cor esteja zerada
            if($productColor->qtd == 0)
            {
                $requestProduct->delete();

                $req->session()->flash('mensagem-falha', "Lamentamos, mas infelizmente ficamos sem stock para este produto com a cor $color!
                                        Sentimos muito mas ele foi deletado do seu carrinho. ");
                return back();
            }

            if($qtd > $productColor->qtd)
            {
                $req->session()->flash('mensagem-falha', "Lamentamos, mas não pode adicionar $qtd unidade(s) ao carrinho, deste produto com a cor $color! ");
                return back();
            }
        }

        if($color == 'Default')
        {
            if($qtd > $product->qtd)
            {
                $req->session()->flash('mensagem-falha', "Lamentamos, mas não pode adicionar $qtd unidade(s) ao carrinho, deste produto! ");
                return back();
            }
        }

        DB::select("UPDATE request_product SET qtd = $qtd WHERE request_id = {$requestID}
        AND product_id = {$productID} AND color = '$color' AND status = 'RE' ");

        $req->session()->flash('mensagem-sucesso', 'Quantidade actualizada com sucesso!');
        return back();

    }

    public function cancel()
    {
        $this->middleware('VerifyCsrfToken');

        $req = Request();
        $requestID = $req->input('request_id');
        $requestProductID = $req->input('id');
        $clientID = Auth::guard('client')->id();





        if( empty($requestProductID) )
        {
            $req->session()->flash('mensagem-falha', 'Nenhum item selecionado para cancelamento');
            return redirect()->route('shopCart.purchases');
        }

        $checkRequest = Pedido::where([
            'id' => $requestID,
            'client_id' => $clientID,
            'status' => 'EC',
        ])->exists();

        if( !$checkRequest )
        {
            $req->session()->flash('mensagem-falha', 'Pedido não encontrado para cancelamento');
            return redirect()->route('shopCart.purchases');
        }

        $checkProducts = RequestProduct::where([
            'request_id' => $requestID,
            'status' => 'EC',
        ])->whereIn('id', $requestProductID)->exists();

        if( !$checkProducts )
        {
            $req->session()->flash('mensagem-falha', 'Produtos do pedido não encontrados');
            return redirect()->route('shopCart.purchases');
        }

        RequestProduct::where([
            'request_id' => $requestID,
            'status' => 'EC',
        ])->whereIn('id', $requestProductID)->update(['status' => 'CA']);

        $checkRequestCancel = RequestProduct::where([
            'request_id' => $requestID,
            'status' => 'EC',
        ])->exists();

        if (!$checkRequestCancel)
        {
            Pedido::where(['id' => $requestID])->update(['status' => 'CA', 'canceled_for' => 'CLIENT']);

            $req->session()->flash('mensagem-sucesso', 'Compra cancelada com sucesso!');
        }
        else
        {
            $req->session()->flash('mensagem-sucesso', 'Item(s) da compra cancelado(s) com sucesso!');
        }

        $productsQtdIncrease = RequestProduct::where([
            'request_id' => $requestID,
            'status' => 'CA',
        ])->whereIn('id', $requestProductID)->get();

        $total_request_product_canceled = 0;

        foreach($productsQtdIncrease as $productQtdIncrease)
        {
            $product = ProductModel::find($productQtdIncrease->product_id);

            ProductModel::where('id', $product->id)->update(['qtd' => $product->qtd + $productQtdIncrease->qtd]);

            $total_request_product_canceled+= $productQtdIncrease->total_of_request_product;
        }

        //dd($total_request_product_canceled);

        Pedido::where(['id' => $requestID])->update(['total_of_request' => $req->total_pedido - $total_request_product_canceled]);

        $purchases = Pedido::where([
            'status' => 'EC',
            'client_id' => $clientID,
        ])->orderBy('created_at', 'DESC')->get();
        foreach($purchases as $purchase)
        {
            $date1 = strtotime( $purchase->created_at );
            $date2 = strtotime(date('d/m/Y H:i'));

            $intervalo = abs( $date2 - $date1 ) / 60;

            if($intervalo >= 1440)
            {
                Pedido::where('id', '=', $purchase->id)->update(['status' => 'CA', 'canceled_for' => 'TIME']);

                RequestProduct::where([
                    'request_id' => $purchase->id,
                    'status' => 'EC',
                ])->update(['status' => 'CA']);
            }
        }

        return redirect()->route('shopCart.purchases');
    }

    public function checkout()
    {
        $clientAddress = Client::find(Auth::guard('client')->id());

        if($clientAddress->province == null || $clientAddress->district == null || $clientAddress->street == null || $clientAddress->neighborhood == null || $clientAddress->telephone == null)
        {
            return redirect()->route('client.showRegister');
        }

        $province = ProvinceModel::find($clientAddress->province);
        $district = DisrictModel::find($clientAddress->district);

        $provinces = DB::select("SELECT * FROM province_tb");
        $districts = DB::select("SELECT * FROM district_tb");

        //---
        $categories = DB::select(" SELECT DISTINCT product_categorie_tb.* FROM product_categorie_tb, product_subcategorie_tb, product_tb
        WHERE product_tb.subcategorie = product_subcategorie_tb.id
        AND product_subcategorie_tb.categorie = product_categorie_tb.id
        AND product_tb.status = 'online' AND product_categorie_tb.eliminado = 'no'  ");

        $subcategories = DB::select(" SELECT DISTINCT product_subcategorie_tb.* FROM product_subcategorie_tb, product_tb
        WHERE product_tb.subcategorie = product_subcategorie_tb.id
        AND product_tb.status = 'online' AND product_subcategorie_tb.eliminado = 'no' ");

        $services = DB::select(" SELECT * FROM service_tb WHERE status = 'online' ");

        $requests = Pedido::where([
            'status' => 'RE',
            'client_id' => Auth::guard('client')->id()
        ])->get();
        //----


        $contacts = DB::select(" SELECT * FROM contacts_doriema ");


        return view('site.shopcart.checkout', [
            'services' => $services,
            'categories' => $categories,
            'subcategories' => $subcategories,
            'requests' => $requests,
            'contacts' => $contacts,

            'clientAddress' => $clientAddress,
            'province' => $province,
            'district' => $district,

            'provinces' => $provinces,
            'districts' => $districts,
        ]);
    }
    public function getDistricts(Request $request, $id)
    {
        if($request->ajax())
        {
            $districts = DB::select("SELECT * FROM district_tb WHERE province={$id}");
            return response()->json($districts);
        }
    }

    //FINALIZA PEDIDO
    public function toEnd()
    {
        $this->middleware('VerifyCsrfToken');

        $req = Request();
        $requestID = $req->input('request_id');
        $clientID = Auth::guard('client')->id();

        if($req->payment_method === null)
        {
            $req->session()->flash('mensagem-falha', 'Desculpa mas a compra não pode ser concluída até informar uma forma de pagamento!');
            return back();
        }

        // Verificar se tem endereço diferente
        if( ($req->address_different === 'on') && ($req->district != null && $req->street != null && $req->neighborhood != null))
        {
            RequestAddress::create([
                'request_id' => $req->request_id,
                'province' => $req->province,
                'district' => $req->district,
                'street' => $req->street,
                'neighborhood' => $req->neighborhood,
                'house_number' => $req->house_number,
            ]);
        }

        if($req->note != null)
        {
            Pedido::where('id', '=', $req->request_id)->update(['note' => $req->note]);
        }

        $checkRequest = Pedido::where([
            'id' => $requestID,
            'client_id' => $clientID,
            'status' => 'RE',
        ])->exists();

        if( !$checkRequest )
        {
            $req->session()->flash('mensagem-falha', 'Pedido não encontrado');
            return redirect()->route('shopCart.purchases');
        }

        $checkProduct = RequestProduct::where([
            'request_id' => $requestID
        ])->exists();

        if( !$checkProduct )
        {
            $req->session()->flash('mensagem-falha', 'Produtos do pedido não encontrados!');
            return redirect()->route('shopCart.purchases');
        }

        $requestProducts = RequestProduct::where('request_id', $requestID)->get();

        $products = ProductModel::all();

        foreach($requestProducts as $requestProduct)
        {
            foreach($products as $product)
            {
                if($requestProduct->product_id === $product->id)
                {
                    if($product->qtd == 0 || $product->qtd < $requestProduct->qtd)
                    {
                        RequestProduct::where('product_id', $requestProduct->product_id)->where('request_id', $requestProduct->request_id)
                        ->delete();
                    }
                    ProductModel::where('id', $requestProduct->product_id)->update(['qtd' => $product->qtd - $requestProduct->qtd]);

                    RequestProduct::where('product_id', $requestProduct->product_id)->where('request_id', $requestProduct->request_id)
                    ->update(['total_of_request_product' => $product->new_price * $requestProduct->qtd]);
                }
            }
        }

        RequestProduct::where([
            'request_id' => $requestID
        ])->update([
            'status' => 'EC',
            'created_at' => date('Y/m/d'),
            'time' => date('H:i:s')
        ]);

        Pedido::where([
            'id' => $requestID
        ])->update([
            'status' => 'EC',
            'payment_method' => $req->payment_method,
            'total_of_request' => $req->total_of_request,
            'created_at' => date('Y/m/d'),
            'time' => date('H:i:s')
        ]);

        $req->session()->flash('mensagem-sucesso', 'Compra concluída com sucesso!');

       return redirect()->route('sendEmail.Compra', $requestID);

    //    return redirect()->route('shopCart.purchases');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        $this->middleware('VerifyCsrfToken');

        $req = Request();

        $requestID = $req->input('request_id');
        $productID = $req->input('product_id');
        $remove_apenas_item = (boolean)$req->input('item');
        $color = $req->input('color');
        $status = $req->input('status');
        $clientID = Auth::guard('client')->id();

        $requestID = Pedido::queryID([
            'id' => $requestID,
            'client_id' => $clientID,
            'status' => 'RE',
        ]);


        if( empty($requestID) )
        {
            $req->session()->flash('mensagem-falha', 'Pedido não encontrado');
            return redirect()->route('shopCart.index');
        }

        $where_product = [
            'request_id' => $requestID,
            'product_id' => $productID,
            'color' => $color,
            'status' => $status,
        ];

        $product = RequestProduct::where($where_product)->orderBy('id', 'DESC')->first();

        if( empty($product->id) )
        {
            $req->session()->flash('mensagem-falha', 'Produto não encontrado no carrinho');
            return redirect()->route('shopCart.index');
        }

        if( $remove_apenas_item )
        {
            $where_product['id'] = $product->id;
        }

        RequestProduct::where($where_product)->delete();

        $checkRequest = RequestProduct::where([
            'request_id' => $product->request_id
        ])->exists();


        if( !$checkRequest )
        {
            Pedido::where([
                'id' => $product->request_id
            ])->delete();
        }

        $req->session()->flash('mensagem-sucesso', 'Produto removido do carrinho com sucesso!');

        return redirect()->route('shopCart.index');
    }
}
