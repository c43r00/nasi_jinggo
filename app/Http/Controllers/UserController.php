<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;

class UserController extends Controller
{
    /**
     * Display a listing of users (employees)
     */
    public function index()
    {
        $users = User::where('role', '!=', 'pemilik')
            ->withCount(['sales', 'productions', 'ingredientPurchases'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Statistics
        $totalEmployees = $users->count();
        $activeEmployees = $users->where('is_active', true)->count();
        $staffDapur = $users->where('role', 'staff_dapur')->count();
        $kasir = $users->where('role', 'kasir')->count();
        
        return view('users.index', compact('users', 'totalEmployees', 'activeEmployees', 'staffDapur', 'kasir'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['staff_dapur', 'kasir'])],
            'phone' => 'nullable|string|max:15',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password harus diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role harus dipilih',
            'role.in' => 'Role tidak valid',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('users.index')->with('success', 'Karyawan berhasil ditambahkan!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Prevent viewing pemilik
        if ($user->role === 'pemilik') {
            abort(403, 'Unauthorized action.');
        }
        
        // Load relationships
        $user->load(['sales', 'productions', 'ingredientPurchases', 'creator']);
        
        // Statistics
        $totalSales = $user->sales()->sum('total_amount');
        $totalTransactions = $user->sales()->count();
        $totalProductions = $user->productions()->sum('quantity_produced');
        $totalPurchases = $user->ingredientPurchases()->sum('total_price');
        
        return view('users.show', compact('user', 'totalSales', 'totalTransactions', 'totalProductions', 'totalPurchases'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Prevent editing pemilik
        if ($user->role === 'pemilik') {
            abort(403, 'Unauthorized action.');
        }
        
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        // Prevent updating pemilik
        if ($user->role === 'pemilik') {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['staff_dapur', 'kasir'])],
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama harus diisi',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'role.required' => 'Role harus dipilih',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Data karyawan berhasil diupdate!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting pemilik
        if ($user->role === 'pemilik') {
            abort(403, 'Unauthorized action.');
        }
        
        // Check if user has transactions
        $hasSales = $user->sales()->exists();
        $hasProductions = $user->productions()->exists();
        $hasPurchases = $user->ingredientPurchases()->exists();
        
        if ($hasSales || $hasProductions || $hasPurchases) {
            return redirect()->route('users.index')->with('error', 'Karyawan tidak dapat dihapus karena memiliki data transaksi. Nonaktifkan saja.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Karyawan berhasil dihapus!');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        // Prevent toggling pemilik
        if ($user->role === 'pemilik') {
            abort(403, 'Unauthorized action.');
        }
        
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('users.index')->with('success', "Karyawan berhasil {$status}!");
    }

    /**
     * Show user profile
     */
    public function profile()
    {
        $user = Auth::user();
        return view('users.profile', compact('user'));
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:15',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        return back()->with('success', 'Profile berhasil diupdate!');
    }

    /**
     * Update user password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama harus diisi',
            'password.required' => 'Password baru harus diisi',
            'password.min' => 'Password baru minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        $user = Auth::user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

    public function exportPdf()
    {
    $users = User::where('role', '!=', 'pemilik')
        ->withCount(['sales', 'productions', 'ingredientPurchases'])
        ->orderBy('created_at', 'desc')
        ->get();
    
    // Statistics
    $totalEmployees = $users->count();
    $activeEmployees = $users->where('is_active', true)->count();
    $staffDapur = $users->where('role', 'staff_dapur')->count();
    $kasir = $users->where('role', 'kasir')->count();
    
    $pdf = Pdf::loadView('users.pdf', compact(
        'users', 
        'totalEmployees', 
        'activeEmployees', 
        'staffDapur', 
        'kasir'
    ));
    
    return $pdf->download('data-karyawan-' . date('Y-m-d') . '.pdf');
    }   
}