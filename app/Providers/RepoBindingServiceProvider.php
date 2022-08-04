<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepoBindingServiceProvider extends ServiceProvider
{
	public function register()
	{
		$app = $this->app;

        /*
        |--------------------------------------------------------------------------
        | Pengaturan
        |--------------------------------------------------------------------------
        */
		$app->bind('\App\Repositories\Contracts\Pengaturan\MenuInterface', function () {
			$repository = new \App\Repositories\Pengaturan\MenuRepository(new \App\Models\Pengaturan\Menu);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\LogActivityInterface', function () {
			$repository = new \App\Repositories\Pengaturan\LogActivityRepository(new \App\Models\Pengaturan\LogActivity);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\AktivitasLogInterface', function () {
			$repository = new \App\Repositories\Pengaturan\AktivitasLogRepository(new \App\Models\Pengaturan\AktivitasLog);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\MenuPenggunaInterface', function () {
			$repository = new \App\Repositories\Pengaturan\MenuPenggunaRepository(new \App\Models\Pengaturan\MenuPengguna);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\RoutesUserInterface', function () {
			$repository = new \App\Repositories\Pengaturan\RoutesUserRepository(new \App\Models\Pengaturan\RoutesUser);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\RoutesInterface', function () {
			$repository = new \App\Repositories\Pengaturan\RoutesRepository(new \App\Models\Pengaturan\Routes);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\ViewRoutesUserInterface', function () {
			$repository = new \App\Repositories\Pengaturan\ViewRoutesUserRepository(new \App\Models\Pengaturan\ViewRoutesUser);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\PreferensiPerusahaanInterface', function () {
			$repository = new \App\Repositories\Pengaturan\PreferensiPerusahaanRepository(new \App\Models\Pengaturan\PreferensiPerusahaan);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengaturan\PenomoranInterface', function () {
			$repository = new \App\Repositories\Pengaturan\PenomoranRepository(new \App\Models\Pengaturan\Penomoran);
			return $repository;
		});

		/*
		|--------------------------------------------------------------------------
		| Pengguna
		|--------------------------------------------------------------------------
		*/
		$app->bind('\App\Repositories\Contracts\Pengguna\AkunInterface', function () {
			$repository = new \App\Repositories\Pengguna\PenggunaRepository(new \App\Models\Pengguna\Pengguna);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pengguna\MenuPenggunaInterface', function () {
			$repository = new \App\Repositories\Pengguna\MenuPenggunaRepository(new \App\Models\Pengguna\MenuPengguna);
			return $repository;
		});

		/*
		|--------------------------------------------------------------------------
		| Perusahaan
		|--------------------------------------------------------------------------
		*/

		$app->bind('\App\Repositories\Contracts\Perusahaan\DepartemenInterface', function () {
			$repository = new \App\Repositories\Perusahaan\DepartemenRepository(new \App\Models\Perusahaan\Departemen);
			return $repository;
		});


		/*
		|-------------------------------------------------------------------------------------------------------------------------------------
		| Pembelian
		|-------------------------------------------------------------------------------------------------------------------------------------
		*/

		$app->bind('\App\Repositories\Contracts\Pembelian\PermintaanPembelianHeaderInterface', function () {
			$repository = new \App\Repositories\Pembelian\PermintaanPembelianHeaderRepository(new \App\Models\Pembelian\PermintaanPembelianHeader);
			return $repository;
		});

		$app->bind('\App\Repositories\Contracts\Pembelian\PermintaanPembelianDetailInterface', function () {
			$repository = new \App\Repositories\Pembelian\PermintaanPembelianDetailRepository(new \App\Models\Pembelian\PermintaanPembelianDetail);
			return $repository;
		});

        $app->bind('\App\Repositories\Contracts\Litbang\KelitbanganInterface', function () {
            $repository = new \App\Repositories\Litbang\KelitbanganRepository(new \App\Models\Litbang\Kelitbangan);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\InovasiInterface', function () {
            $repository = new \App\Repositories\Litbang\InovasiRepository(new \App\Models\Litbang\Inovasi);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\PelaksanaKelitbanganInterface', function () {
            $repository = new \App\Repositories\Litbang\PelaksanaKelitbanganRepository(new \App\Models\Litbang\PelaksanaKelitbangan);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\PelaksanaInovasiInterface', function () {
            $repository = new \App\Repositories\Litbang\PelaksanaInovasiRepository(new \App\Models\Litbang\PelaksanaInovasi);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\InstansiInterface', function () {
            $repository = new \App\Repositories\Litbang\InstansiRepository(new \App\Models\Litbang\Instansi);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\AgendaInterface', function () {
            $repository = new \App\Repositories\Litbang\AgendaRepository(new \App\Models\Litbang\Agenda);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\BeritaInterface', function () {
            $repository = new \App\Repositories\Litbang\BeritaRepository(new \App\Models\Litbang\Berita);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\UsulanPenelitianInterface', function () {
            $repository = new \App\Repositories\Litbang\UsulanPenelitianRepository(new \App\Models\Litbang\UsulanPenelitian);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\UsulanInovasiInterface', function () {
            $repository = new \App\Repositories\Litbang\UsulanInovasiRepository(new \App\Models\Litbang\UsulanInovasi);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\AttachmentInterface', function () {
            $repository = new \App\Repositories\Litbang\AttachmentRepository(new \App\Models\Litbang\Attachment);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Pengguna\PenggunaInterface', function () {
            $repository = new \App\Repositories\Pengguna\PenggunaRepository(new \App\Models\Pengguna\Pengguna);
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\PenomoranInterface', function () {
            $repository = new \App\Repositories\Litbang\PenomoranRepository(new \App\Models\Litbang\Penomoran );
            return $repository;
        });

        $app->bind('\App\Repositories\Contracts\Litbang\SuratKeluarInterface', function () {
            $repository = new \App\Repositories\Litbang\SuratKeluarRepository(new \App\Models\Litbang\SuratKeluar );
            return $repository;
        });

	}
}
