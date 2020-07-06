<?php

namespace App\Http\Controllers\Admim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductModel;
use App\Models\Promotion;

class PromotionController extends Controller
{
    protected $request;
    private $repository;

    public function __construct(Request $request, Promotion $promotion)
    {
        $this->request = $request;
        $this->repository = $promotion;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = ProductModel::all();

        $promotion = Promotion::find(1);

        if($promotion == null)
        {
            return view('admim.promotions.promotion',[
                'products' => $products,
            ]);
        }

        $product01 = ProductModel::where('id', $promotion->product_01)->first();

        $product02 = ProductModel::where('id', $promotion->product_02)->first();

       
        return view('admim.promotions.promotion',[
            'products' => $products,
            'promotion' => $promotion,
            'product01' => $product01,
            'product02' => $product02
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

       $this->repository->create($data);

       return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $products = ProductModel::all();

        $promotion = Promotion::find(01);

        $product01 = ProductModel::where('id', $promotion->product_01)->first();

        $product02 = ProductModel::where('id', $promotion->product_02)->first();

        return view('admim.promotions.edit',[
            'products' => $products,
            'promotion' => $promotion,
            'product01' => $product01,
            'product02' => $product02
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // if(Gate::denies('Editar Produtos'))
        // {
        //     return redirect()->back();
        // }

        if(!$promotion = $this->repository->find(01))
        {
            return redirect()->back();
        }

        $data = $request->all();

        $promotion->update($data);

        return redirect()->route('admim.promotion.index');
    }
    
}
