<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\Rating;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('admin.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            return redirect()->route('admin.index');
        }

        return redirect()->back()->with('error', 'Email atau Password Salah!');
    }

    // Menampilkan halaman register
    public function showRegister()
    {
        return view('admin.register');
    }

    // Proses register admin baru
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.login')->with('success', 'Admin berhasil didaftarkan!');
    }

    // Halaman dashboard admin
    public function dashboard()
    {
        $totalProduk = Product::count();
        $stokHabis = Product::where('stock', 0)->count();
        $ratingBulanIni = Rating::whereMonth('created_at', now()->month)->avg('rating');
        $ratingBulanIni = $ratingBulanIni ? number_format($ratingBulanIni, 1) : 0;

        $ratingPerBulan = Rating::selectRaw('MONTH(created_at) as bulan, AVG(rating) as rata')
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->pluck('rata', 'bulan')
            ->toArray();

        $ratingChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $ratingChart[$i] = isset($ratingPerBulan[$i]) ? round($ratingPerBulan[$i], 2) : 0;
        }

        $ratingChart = array_values($ratingChart);

        $stokRendah = Product::orderBy('stock', 'asc')->take(3)->get();

        return view('admin.index', compact('totalProduk', 'stokHabis', 'ratingBulanIni', 'ratingChart', 'stokRendah'));
    }

    // Halaman manajemen produk
    public function produk()
    {
        return view('admin.produk'); // Buat file resources/views/admin/produk.blade.php nanti
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        /** @var \App\Models\Admin $admin */
        $admin = Auth::guard('admin')->user();

        if (!Hash::check($request->old_password, $admin->password)) {
            return back()->withErrors(['old_password' => 'Password lama salah!']);
        }

        $admin->password = Hash::make($request->new_password);
        $admin->save();

        Auth::guard('admin')->logout();

        return redirect()->route('admin.login')->with('success', 'Password berhasil diubah, silakan login kembali.');
    }

    // Logout admin
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
