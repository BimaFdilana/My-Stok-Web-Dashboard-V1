<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\KasirPermission;
use App\Models\KasirSchedule;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KasirManagementController extends Controller
{
    public static $menuOptions = [
        'dashboard' => 'Dashboard',
        'kasir' => 'Kasir',
        'barang' => 'Barang',
        'qris' => 'Pengaturan QRIS',
        'stok' => 'Stok',
        'barang_masuk' => 'Barang Masuk',
        'barang_keluar' => 'Barang Keluar',
        'laporan_masuk' => 'Laporan Masuk',
        'laporan_keluar' => 'Laporan Keluar',
        'laporan_transaksi' => 'Laporan Transaksi',
    ];

    public function index()
    {
        $kasirs = User::where('role', 'kasir')->with('permissions', 'schedules')->get();
        return view('kasir-management.index', compact('kasirs'));
    }

    public function create()
    {
        return view('kasir-management.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'nama_pemilik' => $request->nama_pemilik,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'kasir',
        ]);

        KasirPermission::insert([
            ['user_id' => $user->id, 'menu_key' => 'dashboard', 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $user->id, 'menu_key' => 'kasir', 'created_at' => now(), 'updated_at' => now()],
        ]);

        return redirect()->route('kasir-management.index')->with('success', 'Kasir berhasil ditambahkan');
    }

    public function edit($id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);
        return view('kasir-management.edit', compact('kasir'));
    }

    public function update(Request $request, $id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nama_pemilik' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $data = $request->only('name', 'nama_pemilik', 'username', 'email');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $kasir->update($data);

        return redirect()->route('kasir-management.index')->with('success', 'Data kasir berhasil diperbarui');
    }

    public function destroy($id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);
        $kasir->delete();

        return redirect()->route('kasir-management.index')->with('success', 'Kasir berhasil dihapus');
    }

    public function permissions($id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);
        $currentPermissions = $kasir->permissions->pluck('menu_key')->toArray();
        $menuOptions = self::$menuOptions;

        return view('kasir-management.permissions', compact('kasir', 'currentPermissions', 'menuOptions'));
    }

    public function updatePermissions(Request $request, $id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);

        $menus = $request->input('menus', []);
        $menus = array_unique(array_merge(['dashboard', 'kasir'], $menus));

        KasirPermission::where('user_id', $kasir->id)->delete();

        foreach ($menus as $menu) {
            KasirPermission::create(['user_id' => $kasir->id, 'menu_key' => $menu]);
        }

        return redirect()->route('kasir-management.permissions', $kasir->id)->with('success', 'Akses menu berhasil diperbarui');
    }

    public function schedule($id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);
        $schedules = $kasir->schedules->keyBy('hari');
        $days = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu'];

        return view('kasir-management.schedule', compact('kasir', 'schedules', 'days'));
    }

    public function updateSchedule(Request $request, $id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);

        KasirSchedule::where('user_id', $kasir->id)->delete();

        $days = $request->input('days', []);
        foreach ($days as $hari => $data) {
            if (!empty($data['aktif'])) {
                KasirSchedule::create([
                    'user_id' => $kasir->id,
                    'hari' => $hari,
                    'jam_masuk' => $data['jam_masuk'],
                    'jam_keluar' => $data['jam_keluar'],
                ]);
            }
        }

        return redirect()->route('kasir-management.schedule', $kasir->id)->with('success', 'Jadwal kerja berhasil diperbarui');
    }

    public function sales($id)
    {
        $kasir = User::where('role', 'kasir')->findOrFail($id);
        $transactions = Transaction::where('user_id', $kasir->id)
            ->with('details.item')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('kasir-management.sales', compact('kasir', 'transactions'));
    }
}
