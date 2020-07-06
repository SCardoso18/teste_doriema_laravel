<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Models\Client;
use App\Models\ClientContact;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ClientLoginController extends Controller
{
    protected $request;
    private $repository;

    public function __construct(Request $request, Client $client)
    {
        $this->request = $request;
        $this->repository = $client;
    }


    public function showRegister()
    {
        $provinces = DB::select("SELECT * FROM province_tb");
        $districts = DB::select("SELECT * FROM district_tb");

        return view('site.client.clientRegister', [
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

    public function register(Request $request)
    {
        if($request->password != $request->confirm_password)
        {
            return redirect()->back()->withInput()->withErrors(['As palavras passes diferem!']);
        }

        $data = $request->all();

        $data['password'] = Hash::make($data['password']);

       $this->repository->create($data);

       $client = Client::where('email', '=', $request->email)->first();

       ClientContact::create([
        'client_id' => $client->id,
        'telephone' => $request->telephone2,
        ]);


       $credentials = [
        'email' => $request->email,
        'password' => $request->password
        ];

        if (Auth::guard('client')->attempt($credentials))
        {
            // return redirect()->route('products.showHome');

            return redirect()->route('sendEmail.newClient');
        }


       return redirect()->route('products.showHome');
    }

    public function updateRegister(Request $request)
    {
        $clientID = Auth::guard('client')->id();

        //dd($clientID);

        //dd($request->all());

       Client::where('id', '=', $clientID)
       ->update([
           'telephone' => $request->telephone,
           'province' => $request->province,
           'district' => $request->district,
           'neighborhood' => $request->neighborhood,
           'street' => $request->street,
           'house_number' => $request->house_number,
       ]);

       ClientContact::create([
        'client_id' => $clientID,
        'telephone' => $request->telephone2,
        ]);

       return redirect()->route('shopCart.checkout');
    }

    public function showLogin()
    {
        return view('site.client.clientLogin');
    }


    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password'  => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::guard('client')->attempt($credentials))
        {
            return redirect()->route('products.showHome');
        }

        return redirect()->back()->withInput()->withErrors(['Os dados não conferem']);
    }

    public function logout()
    {
        Auth::guard('client')->logout();

        return redirect()->route('products.showHome');
    }
}
