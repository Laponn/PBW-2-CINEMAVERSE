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
        View::composer('*', function ($view) {

            $branches = Branch::orderBy('city')
                ->orderBy('name')
                ->get();

            // Jika belum ada cabang sama sekali
            if ($branches->isEmpty()) {
                $view->with('globalBranches', collect())
                     ->with('navBranches', collect())
                     ->with('currentBranchId', null)
                     ->with('currentBranchName', 'Pilih Lokasi');
                return;
            }

            // Ambil cabang terpilih dari session, fallback ke cabang pertama
            $currentBranchId = Session::get(
                'selected_branch_id',
                $branches->first()->id
            );

            $currentBranch = $branches->firstWhere('id', $currentBranchId);

            $view->with('globalBranches', $branches)
                 ->with('navBranches', $branches)
                 ->with('currentBranchId', $currentBranchId)
                 ->with(
                     'currentBranchName',
                     $currentBranch?->name ?? 'Pilih Lokasi'
                 );
        });
    }
}
