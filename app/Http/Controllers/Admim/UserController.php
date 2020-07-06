<?php

namespace App\Http\Controllers\Admim;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Gate;


class UserController extends Controller
{
    protected $request;
    private $repository;

    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->repository = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('Cadastrar Usuarios') && Gate::denies('Visualizar Usuarios'))
        {
            return redirect()->back();
        }

        $users = DB::select(" SELECT * FROM users WHERE eliminado = 'no' ");

        return view('admim.users.createUser', [
            'users' => $users,
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
        if(Gate::denies('Cadastrar Usuarios'))
        {
            return redirect()->back();
        }

        if($request->password != $request->comfirm_password)
        {
            return redirect()->back()->withInput()->withErrors(['As palavras passes são diferentes']);
        }

        $data = $request->all();

        $data['password'] = Hash::make($data['password']);

       $this->repository->create($data);

       return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(Gate::denies('Visualizar Usuarios'))
        {
            return redirect()->back();
        }

        $user = User::find($id);

        if (!$user || $user->eliminado == 'yes') {
            return redirect()->back();
        }

        $roles = DB::select(" SELECT role_user.id as id, role.name as role, role.label as label
        FROM role_user, role, users
        WHERE user_id = users.id AND role_id=role.id
        AND user_id = {$id} ");

        $allRoles = DB::select(" SELECT * FROM role ");

        return view('admim.users.showUser', [
            'user' => $user,
            'roles' => $roles,
            'allRoles' => $allRoles,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(Gate::denies('Editar Usuarios'))
        {
            return redirect()->back();
        }

        $user = User::find($id);

        if(!$user)
        {
            return redirect()->back();
        }

        if($user->eliminado == 'yes')
        {
            return redirect()->back();
        }

        return view('admim.users.editUser', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(Gate::denies('Editar Usuarios'))
        {
            return redirect()->back();
        }

        if(!$user = $this->repository->find($id))
        {
            return redirect()->back();
        }

        if($user->eliminado == 'yes')
        {
            return redirect()->back();
        }

        if($request->password != $request->comfirm_password)
        {
            var_dump('Fudeu');
            dd($request->all());
        }

        $data = $request->all();

        $data['password'] = Hash::make($data['password']);

        $user->update($data);

       return redirect()->route('admim.users.show', $request->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Gate::denies('Apagar Usuarios'))
        {
            return redirect()->back();
        }

        $user = $this->repository->where('id', $id)->first();

        if(!$user)
        {
            return redirect()->back();
        }

        if($user->eliminado == 'yes')
        {
            return redirect()->back();
        }

        DB::select("UPDATE users SET eliminado = 'yes'WHERE id=$user->id");

        //$user->delete();

        return redirect()->route('admim.users.index');
    }
}
