<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Models\User;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    use WithPagination;    

    public function __construct()
    {
        // $this->middleware('can:admin.roles.index')->only('index');
        // $this->middleware('can:admin.roles.create')->only('create', 'store');
        // $this->middleware('can:admin.roles.edit')->only('edit', 'update');
        // $this->middleware('can:admin.roles.delete')->only('destroy');
    }

    public function index()
    {   
        $users = Company::find(session('company')->id)->users()->paginate(10);        
        
        return view('users.index', compact('users'));
    }

    public function create()
    {   
        $roles = Role::whereNotIn('name', ['Super Administrador'])->get();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',                                       
            'password' => [
                'required',
                'max:15',
                Password::min(6)                
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],
            'password_confirmation' => 'required|same:password'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        
        $user->companies()->sync(session('company')->id);
        $user->roles()->sync($request->roles);

        return redirect()->route('users.index');

    }

    public function edit(User $user)
    {
        $roles = Role::all();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id           
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        $user->roles()->sync($request->roles);

        return redirect()->route('users.index');
    }  

    public function destroy(User $user)
    {                
        $user->delete();
        return redirect()->route('users.index');
            
    }

    // Mensaje de información
    public function info($message)
    {
        $this->dispatchBrowserEvent('info', 
            [
                'message' => $message,                       
            ]
        );       
    } 
}
