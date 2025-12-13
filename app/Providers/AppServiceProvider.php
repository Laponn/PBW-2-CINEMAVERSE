<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
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
        //
        View::composer('*', function ($view) {
        $branches = Branch::all();
        
        // Cek apakah user sudah memilih cabang di session?
        // Jika belum, default ke cabang pertama
        $currentBranchId = Session::get('selected_branch_id', $branches->first()->id ?? null);
        $currentBranchName = $branches->where('id', $currentBranchId)->first()->name ?? 'Pilih Lokasi';

        $view->with('globalBranches', $branches)
             ->with('currentBranchId', $currentBranchId)
             ->with('currentBranchName', $currentBranchName);
    });
    }
}
