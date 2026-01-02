<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use App\Models\Branch;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            // Cache daftar branch agar tidak query DB terus-terusan tiap render view
            $branches = Cache::remember('branches:all', 60, function () {
                return Branch::orderBy('city')
                    ->orderBy('name')
                    ->get();
            });

            // Jika belum ada cabang sama sekali
            if ($branches->isEmpty()) {
                $view->with([
                    'globalBranches'   => collect(),
                    'navBranches'      => collect(),
                    'currentBranchId'  => null,
                    'currentBranchName'=> 'Pilih Lokasi',
                ]);
                return;
            }

            // Ambil cabang terpilih dari session, fallback ke cabang pertama
            $fallbackId = $branches->first()->id;
            $currentBranchId = Session::get('selected_branch_id', $fallbackId);

            // Kalau session id tidak valid (cabangnya sudah dihapus), fallback lagi
            $currentBranch = $branches->firstWhere('id', $currentBranchId);
            if (!$currentBranch) {
                $currentBranchId = $fallbackId;
                $currentBranch = $branches->firstWhere('id', $currentBranchId);
                Session::put('selected_branch_id', $currentBranchId);
            }

            $view->with([
                'globalBranches'    => $branches,
                'navBranches'       => $branches,
                'currentBranchId'   => $currentBranchId,
                'currentBranchName' => $currentBranch?->name ?? 'Pilih Lokasi',
            ]);
        });
    }
}
