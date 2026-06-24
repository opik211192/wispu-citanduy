<?php

namespace App\Providers;

use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

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
        // Paksa semua URL memakai HTTPS bila APP_URL memakai https
        // (mencegah error mixed content saat server di belakang proxy/SSL)
        if (str_starts_with((string) config('app.url'), 'https')) {
            URL::forceScheme('https');
        }

        // Hanya akun admin (username 'admin') yang boleh mengelola user
        Gate::define('manage-users', function (User $user) {
            return $user->username === 'admin';
        });

        // Gunakan style pagination Bootstrap 4 (AdminLTE)
        Paginator::useBootstrapFour();

        // Lonceng notifikasi laporan baru di navbar admin
        Event::listen(BuildingMenu::class, function (BuildingMenu $event) {
            if (! auth()->check()) {
                return;
            }

            $unreadCount = Pengaduan::where('is_read', false)->count();

            $event->menu->add([
                'type' => 'navbar-notification',
                'id' => 'notif-laporan',
                'icon' => 'fas fa-bell',
                'icon_color' => 'warning',
                'route' => 'data-pengaduan',
                'label' => $unreadCount,
                'label_color' => 'danger',
                'topnav_right' => true,
                'dropdown_mode' => true,
                'dropdown_flabel' => 'Lihat semua laporan',
                'update_cfg' => [
                    'route' => 'pengaduan.notifikasi',
                    'period' => 30,
                ],
            ]);
        });
    }
}
