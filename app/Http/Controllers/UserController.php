<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\EmailConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    // ✅ Middleware for permission control (uncomment if needed)
    /*
    public function __construct()
    {
        $this->middleware('permission:view user', ['only' => ['index']]);
        $this->middleware('permission:create user', ['only' => ['create','store']]);
        $this->middleware('permission:update user', ['only' => ['edit','update']]);
        $this->middleware('permission:delete user', ['only' => ['destroy']]);
    }
    */

    # ✅ List Users
    public function index()
    {
        $users = User::with('roles')->get(); // Load roles for display
        return view('Users.user_index', compact('users'));
    }

    # ✅ Show Create User Form
    public function create()
    {
        $allRoles = Role::all();
        return view('Users.user_create', compact('allRoles'));
    }

    # ✅ Store New User
    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password'      => ['required', 'confirmed', 'min:8'],
            'phone'         => ['required', 'string'],
            'roles'         => ['required', 'array'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        // ✅ Upload profile image
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
        }

        // ✅ Create user
        $user = User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone'         => '+966' . ltrim($request->phone, '+966'), // Ensure Saudi prefix
            'profile_image' => $profileImagePath,
        ]);

        // ✅ Assign roles
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('status', 'User created successfully with roles.');
    }

    # ✅ Show Edit User Form
    public function edit(User $user)
    {
        $allRoles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('Users.user_edit', compact('user', 'allRoles', 'userRoles'));
    }

    # ✅ Update User
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string',
            'password'      => 'nullable|string|min:8|max:20',
            'roles'         => 'required|array',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = User::findOrFail($id);

        $user->name = $request->name;
        $user->phone = '+966' . ltrim($request->phone, '+966');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            $profileImagePath = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $profileImagePath;
        }

        $user->save();

        // ✅ Update roles
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }

    # ✅ Delete User
    public function destroy($userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return redirect()->route('users.index')->with('status', 'User deleted successfully.');
    }

    public function emailconfig(){
        $data = EmailConfiguration::first();
        return view('Email.email_config',compact('data'));
    }

   
public function updateemailconfig(Request $request)
{
    try {
        $request->validate([
            'mailer' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|integer',
            'encryption' => 'nullable|string',
            'username' => 'required|string',
            'password' => 'required|string',
            'from_address' => 'required|email',
        ]);

        // Save to DB
        $config = EmailConfiguration::firstOrCreate([]);
        $config->update([
            'app_name' => $request->app_name,
            'mailer' => $request->mailer,
            'host' => $request->host,
            'port' => $request->port,
            'encryption' => $request->encryption,
            'username' => $request->username,
            'password' => $request->password,
            'from_address' => $request->from_address,
        ]);

        // Save to .env
        $this->setEnvValue([
            'MAIL_FROM_NAME'  => $request->app_name,
            'MAIL_MAILER'     => $request->mailer,
            'MAIL_HOST'       => $request->host,
            'MAIL_PORT'       => $request->port,
            'MAIL_ENCRYPTION' => $request->encryption,
            'MAIL_USERNAME'   => $request->username,
            'MAIL_PASSWORD'   => $request->password,
            'MAIL_FROM_ADDRESS' => $request->from_address,
        ]);

        return redirect()->back()->with('success', 'Email configuration updated successfully!');
    } catch (\Exception $e) {
        Log::error('Email Configuration Update Error: ' . $e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);

        return redirect()->back()->with('error', 'Something went wrong while updating email configuration.');
    }
}


protected function setEnvValue(array $values)
{
    $envPath = base_path('.env');

    if (!file_exists($envPath)) {
        throw new \Exception(".env file not found");
    }

    $env = file_get_contents($envPath);

    foreach ($values as $key => $value) {
        $pattern = "/^{$key}=.*/m";
        $replacement = "{$key}=\"{$value}\"";

        if (preg_match($pattern, $env)) {
            $env = preg_replace($pattern, $replacement, $env);
        } else {
            $env .= PHP_EOL . $replacement;
        }
    }

    file_put_contents($envPath, $env);
}






    }