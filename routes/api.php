<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\MessageConstant;
use App\Http\Controllers\APIController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

/*
|--------------------------------------------------------------------------
| Pengguna
|--------------------------------------------------------------------------
*/

$api->version('v1', ['namespace' => 'App\Http\Controllers'],function ($api) {
	/*
	|--------------------------------------------------------------------------
	| Auth
	|--------------------------------------------------------------------------
	*/
	$api->group(['prefix' => 'auth'], function ($api) {
		// $api->POST('login',  'AuthController@login');
		// $api->GET('logout',  'AuthController@logout');
		$api->POST('login',  'Pengguna\PenggunaController@login');
		$api->POST('refresh',  'Pengguna\PenggunaController@refreshToken');
		$api->GET('logout',  'AuthController@logout');
		//$api->GET('refresh',  'AuthController@refresh')->middleware('auth:api');
		$api->GET('user', 'AuthController@me')->middleware('auth:api');
		$api->GET('need', function (){
			return APIController::respondUnauthorized('Need Login');
		} )->name('need-login');
        $api->GET('check', function (){
            $response['status'] = true;
            $response['status_code'] = 200;
            $response['message'] = 'Ok';
            return $response;
        } )->name('need-login')->middleware('auth:api');
	});

	/*
	|--------------------------------------------------------------------------
	| Dashboard
	|--------------------------------------------------------------------------
	*/

	$api->group(['prefix' => 'dashboard'], function ($api) {
		$api->GET('/', 'Litbang\DashboardController@list');
		$api->POST('request', 'Dashboard\DashboardController@getDataPerRequest')->middleware('auth:api');
		$api->GET('beban-per-minggu', 'Dashboard\DashboardController@bebanDanLainPerMinggu')->middleware('auth:api');
		$api->GET('beban-per-bulan', 'Dashboard\DashboardController@bebanDanLainPerBulan')->middleware('auth:api');
        $api->GET('check', function (){
            $response['status'] = true;
            $response['status_code'] = 200;
            $response['message'] = 'Ok';
            return $response;
        } )->middleware('auth:api');
	});

	/*
	|--------------------------------------------------------------------------
	| Laporan
	|--------------------------------------------------------------------------
	*/

	$api->group(['prefix' => 'laporan'], function ($api) {
		//$api->GET('/', 'Dashboard\DashboardController@list')->middleware('auth:api');
		$api->get('neraca', 'Accounting\LaporanController@getNeraca')->middleware('auth:api');
		$api->post('data', 'Accounting\LaporanController@dataLaporan')->middleware('auth:api');
		$api->get('neraca-per-minggu', 'Accounting\LaporanController@getNeracaPerMinggu')->middleware('auth:api');
		$api->get('neraca-per-bulan', 'Accounting\LaporanController@getNeracaPerBulan')->middleware('auth:api');
		$api->post('neraca-per-tanggal', 'Accounting\LaporanController@getNeracaPerTanggal')->middleware('auth:api');
		$api->POST('get', 'Accounting\LaporanController@getLaporan')->middleware('auth:api');
		$api->get('laba-rugi', 'Accounting\LaporanController@getLabaRugi')->middleware('auth:api');
		$api->get('laba-rugi-per-minggu', 'Accounting\LaporanController@getLabaRugiPerMinggu')->middleware('auth:api');
		$api->get('laba-rugi-per-bulan', 'Accounting\LaporanController@getLabaRugiPerBulan')->middleware('auth:api');
		$api->post('laba-rugi-per-tanggal', 'Accounting\LaporanController@getLabaRugiPerTanggal')->middleware('auth:api');
		// $api->GET('beban-per-minggu', 'Dashboard\DashboardController@bebanDanLainPerMinggu')->middleware('auth:api');
		// $api->GET('beban-per-bulan', 'Dashboard\DashboardController@bebanDanLainPerBulan')->middleware('auth:api');

        ## Penjualan
		$api->get('penjualan', 'Accounting\LaporanController@getLaporanPenjualan')->middleware('auth:api');
		$api->post('penjualan-per-pelanggan', 'Accounting\LaporanController@getLaporanPenjualanPerPelanggan')->middleware('auth:api');
		$api->post('penjualan-per-pelanggan-rinci', 'Accounting\LaporanController@getLaporanPenjualanPerPelangganRinci')->middleware('auth:api');
		$api->post('penjualan-per-barang', 'Accounting\LaporanController@getLaporanPenjualanPerBarang')->middleware('auth:api');
        $api->post('penjualan-per-barang-rinci', 'Accounting\LaporanController@getLaporanPenjualanPerBarangRinci')->middleware('auth:api');
        $api->post('penjualan-per-barang-advance', 'Accounting\LaporanController@getLaporanPenjualanPerBarangAdvance')->middleware('auth:api');

		## Pembelian
		$api->post('pembelian-per-pemasok', 'Accounting\LaporanController@pembelianPerPemasok')->middleware('auth:api');
        $api->post('pembelian-per-pemasok-rinci', 'Accounting\LaporanController@pembelianPerPemasokRinci')->middleware('auth:api');
		$api->post('pembelian-per-barang', 'Accounting\LaporanController@pembelianPerBarang')->middleware('auth:api');
        $api->post('pembelian-per-barang-rinci', 'Accounting\LaporanController@pembelianPerBarangRinci')->middleware('auth:api');
		$api->post('pembelian-uang-muka', 'Accounting\LaporanController@uangMukaPembelian')->middleware('auth:api');

		## Arus Kas
		$api->get('arus-kas', 'Accounting\LaporanController@getArusKas')->middleware('auth:api');

	});


	/*
	|--------------------------------------------------------------------------
	| Pengguna
	|--------------------------------------------------------------------------
	*/
	$api->group(['prefix' => 'pengguna'], function ($api) {
		$api->GET('list','Pengguna\PenggunaController@list')->middleware('auth:api');
		$api->group(['prefix' => 'get'], function ($api) {
			$api->POST('id','Pengguna\PenggunaController@getById')->middleware('auth:api');
		});
		$api->POST('create','Pengguna\PenggunaController@create')->middleware('auth:api');
		$api->POST('update','Pengguna\PenggunaController@update')->middleware('auth:api');
		$api->POST('update-akses','Pengguna\PenggunaController@updateAkses')->middleware('auth:api');
		$api->POST('update-password','Pengguna\PenggunaController@updatePassword')->middleware('auth:api');
		$api->POST('delete','Pengguna\PenggunaController@delete')->middleware('auth:api');
		$api->POST('route-check','Pengguna\PenggunaController@routeAvailableCheck')->middleware('auth:api');
        $api->POST('android-check','Pengguna\PenggunaController@cekAksesAndroid')->middleware('auth:api');
        $api->POST('android-get','Pengguna\PenggunaController@getAksesAndroid')->middleware('auth:api');
        $api->POST('update-akses-android','Pengguna\PenggunaController@updateAksesAndroid')->middleware('auth:api');
	});

	/*
	|--------------------------------------------------------------------------
	| Pengguna - Group Pengguna
	|--------------------------------------------------------------------------
	*/
	$api->group(['prefix' => 'group-pengguna'], function ($api) {
		$api->GET('list','Pengguna\GroupPenggunaController@list')->middleware('auth:api');
		$api->group(['prefix' => 'get'], function ($api) {
			$api->POST('id','Pengguna\GroupPenggunaController@getById')->middleware('auth:api');
		});
		$api->POST('create','Pengguna\GroupPenggunaController@create')->middleware('auth:api');
		$api->POST('update','Pengguna\GroupPenggunaController@update')->middleware('auth:api');
		$api->POST('delete','Pengguna\GroupPenggunaController@delete')->middleware('auth:api');

	});

	/*
	|--------------------------------------------------------------------------
	| Pengaturan - Menu
	|--------------------------------------------------------------------------
	*/
	$api->group(['prefix' => 'menu'], function ($api) {
		$api->GET('list','Pengaturan\MenuController@list');
		$api->group(['prefix' => 'get'], function ($api) {
			$api->POST('id','Pengaturan\MenuController@getById')->middleware('auth:api');
			$api->POST('pengguna','Pengaturan\MenuController@getByPengguna')->middleware('auth:api');
		});

		$api->POST('add','Pengaturan\MenuController@add')->middleware('auth:api');
		$api->POST('update','Pengaturan\MenuController@update')->middleware('auth:api');
		$api->POST('delete','Pengaturan\MenuController@delete')->middleware('auth:api');

	});

	/*
	|--------------------------------------------------------------------------
	| Pengaturan - Menu
	|--------------------------------------------------------------------------
	*/
	$api->group(['prefix' => 'preferensi'], function ($api) {
		$api->GET('list','Pengaturan\PreferensiPerusahaanController@list')->middleware('auth:api');
		$api->POST('update','Pengaturan\PreferensiPerusahaanController@update')->middleware('auth:api');
		$api->GET('akun-barang-jasa','Pengaturan\PreferensiPerusahaanController@akunTerpakai')->middleware('auth:api');
		$api->GET('reset','Pengaturan\PreferensiPerusahaanController@resetTransaksi')->middleware('auth:api');
	});

	/*
	|--------------------------------------------------------------------------
	| Pengaturan - Routes
	|--------------------------------------------------------------------------
	*/
	$api->group(['prefix' => 'routes'], function ($api) {
		$api->GET('list','Pengaturan\RoutesController@list')->middleware('auth:api');
		$api->GET('report','Pengaturan\RoutesController@displayReport')->middleware('auth:api');
		$api->GET('tree','Pengaturan\RoutesController@tree')->middleware('auth:api');
		$api->group(['prefix' => 'get'], function ($api) {
			$api->POST('id','Pengaturan\RoutesController@getById')->middleware('auth:api');
			$api->POST('by-pengguna','Pengaturan\RoutesController@getByPengguna')->middleware('auth:api');
		});
		$api->POST('create','Pengaturan\RoutesController@create')->middleware('auth:api');
		$api->POST('update','Pengaturan\RoutesController@update')->middleware('auth:api');
		$api->POST('delete','Pengaturan\RoutesController@delete')->middleware('auth:api');
	});

	/*
	|--------------------------------------------------------------------------
	| Pengaturan - Aktivitas Log
	|--------------------------------------------------------------------------
	*/
	$api->group(['prefix' => 'aktivitas-log'], function ($api) {
		$api->GET('list','Pengaturan\AktivitasLogController@list')->middleware('auth:api');
		$api->group(['prefix' => 'get'], function ($api) {
			$api->POST('id','Pengaturan\AktivitasLogController@getById')->middleware('auth:api');
		});
		$api->POST('delete','Pengaturan\AktivitasLogController@delete')->middleware('auth:api');
	});

	/*
	|--------------------------------------------------------------------------
	| Pengaturan - Penomoran
	|--------------------------------------------------------------------------
	*/
	$api->group(['prefix' => 'penomoran'], function ($api) {
		$api->GET('list','Pengaturan\PenomoranController@list')->middleware('auth:api');
		$api->group(['prefix' => 'get'], function ($api) {
			$api->POST('id','Pengaturan\PenomoranController@getById')->middleware('auth:api');
		});
		$api->POST('create','Pengaturan\PenomoranController@create')->middleware('auth:api');
		$api->POST('update','Pengaturan\PenomoranController@update')->middleware('auth:api');
		$api->POST('delete','Pengaturan\PenomoranController@delete')->middleware('auth:api');
	});

    /*
    |--------------------------------------------------------------------------
    | Pengaturan - DB Backup
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'backup-db'], function ($api) {
        $api->GET('list','Pengaturan\BackupDbController@list')->middleware('auth:api');
        $api->POST('start','Pengaturan\BackupDbController@start')->middleware('auth:api');
    });

	/*
	|--------------------------------------------------------------------------
	| Perusahaan - Departemen
	|--------------------------------------------------------------------------
	*/
	$api->group(['prefix' => 'departement'], function ($api) {
		$api->GET('list','Perusahaan\DepartemenController@list')->middleware('auth:api');
		$api->group(['prefix' => 'get'], function ($api) {
			$api->POST('id','Perusahaan\DepartemenController@getById')->middleware('auth:api');
			$api->POST('activity','Perusahaan\DepartemenController@getActivity')->middleware('auth:api');
		});
		$api->POST('create','Perusahaan\DepartemenController@create')->middleware('auth:api');
		$api->POST('update','Perusahaan\DepartemenController@update')->middleware('auth:api');
		$api->POST('delete','Perusahaan\DepartemenController@delete')->middleware('auth:api');
	});


	/*
 |--------------------------------------------------------------------------
 | Pembelian - Permintaan Pembelian
 |--------------------------------------------------------------------------
 */
	$api->group(['prefix' => 'permintaan-pembelian'], function ($api) {
		$api->group(['prefix' => 'list'], function ($api) {
			$api->GET('/','Pembelian\PermintaanPembelianController@list')->middleware('auth:api');
            $api->GET('/datatable','Pembelian\PermintaanPembelianController@listWithDatatable')->middleware('auth:api');
			$api->POST('/per-tanggal','Pembelian\PermintaanPembelianController@listByTanggal')->middleware('auth:api');
            $api->POST('/per-tanggal-datatable','Pembelian\PermintaanPembelianController@listByTanggalWithDatatable')->middleware('auth:api');
			$api->POST('/','Pembelian\PermintaanPembelianController@list_by')->middleware('auth:api');
			$api->POST('/temp','Pembelian\PermintaanPembelianController@temp')->middleware('auth:api');
			$api->POST('/belum-selesai','Pembelian\PermintaanPembelianController@permintaanPembelianBelumSelesai')->middleware('auth:api');
			$api->POST('/belum-selesai-detail','Pembelian\PermintaanPembelianController@permintaanPembelianBelumSelesaiDetail')->middleware('auth:api');
		});
		$api->group(['prefix' => 'get'], function ($api) {
			$api->POST('id','Pembelian\PermintaanPembelianController@getById')->middleware('auth:api');
			$api->POST('pemasok','Pembelian\PermintaanPembelianController@getByPemasok')->middleware('auth:api');
			$api->POST('activity','Pembelian\PermintaanPembelianController@getActivity')->middleware('auth:api');
            $api->GET('numbering','Pembelian\PermintaanPembelianController@getNumbering')->middleware('auth:api');
		});
		$api->group(['prefix' => 'listBy'], function ($api) {
			$api->POST('pemasok','Pembelian\PermintaanPembelianController@getByPemasok')->middleware('auth:api');
		});
		$api->POST('create','Pembelian\PermintaanPembelianController@create')->middleware('auth:api');
		$api->POST('update','Pembelian\PermintaanPembelianController@update')->middleware('auth:api');
		$api->POST('delete','Pembelian\PermintaanPembelianController@delete')->middleware('auth:api');

	});

    /*
    |--------------------------------------------------------------------------
    | Kelitbangan
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'kelitbangan'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\KelitbanganController@list');
            $api->GET('/datatable','Litbang\KelitbanganController@listWithDatatable')->middleware('auth:api');
            $api->POST('/datatable-tanggal','Litbang\KelitbanganController@listWithDatatableByTanggal')->middleware('auth:api');

            $api->GET('/bidang','Litbang\BidangKelitbanganController@list');
            $api->GET('/bidang-datatable','Litbang\BidangKelitbanganController@listWithDataTable');
            $api->POST('/by-bidang','Litbang\KelitbanganController@listWithDatatableByBidang');

            $api->POST('/by-bidang-api','Litbang\KelitbanganController@listByBidang');
            $api->POST('/by-bidang-limit','Litbang\KelitbanganController@listByBidangWithLimit');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\KelitbanganController@getById');

            $api->POST('bidang','Litbang\BidangKelitbanganController@getById');
         });
        $api->POST('create','Litbang\KelitbanganController@create')->middleware('auth:api');
        $api->POST('update','Litbang\KelitbanganController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\KelitbanganController@delete')->middleware('auth:api');
        $api->GET('terkini','Litbang\KelitbanganController@terkini');
        $api->GET('nomor','Litbang\KelitbanganController@getAutoNomor');

        $api->POST('create-bidang','Litbang\BidangKelitbanganController@create')->middleware('auth:api');
        $api->POST('update-bidang','Litbang\BidangKelitbanganController@update')->middleware('auth:api');
    });

    /*
    |--------------------------------------------------------------------------
    | Inovasi
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'inovasi'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\InovasiController@list');
            $api->GET('/datatable','Litbang\InovasiController@listWithDatatable')->middleware('auth:api');
            $api->POST('/datatable-tanggal','Litbang\InovasiController@listWithDatatableByTanggal')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\InovasiController@getById');
        });
        $api->POST('create','Litbang\InovasiController@create')->middleware('auth:api');
        $api->POST('update','Litbang\InovasiController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\InovasiController@delete')->middleware('auth:api');
        $api->GET('terkini','Litbang\InovasiController@terkini');
        $api->GET('nomor','Litbang\InovasiController@getAutoNomor');
    });

    /*
    |--------------------------------------------------------------------------
    | Instansi
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'instansi'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\InstansiController@list');
            $api->GET('/datatable','Litbang\InstansiController@listWithDatatable')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\InstansiController@getById')->middleware('auth:api');
        });
        $api->POST('create','Litbang\InstansiController@create')->middleware('auth:api');
        $api->POST('update','Litbang\InstansiController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\InstansiController@delete')->middleware('auth:api');
    });

    /*
    |--------------------------------------------------------------------------
    | Agenda
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'agenda'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\AgendaController@list');
            $api->GET('/datatable','Litbang\AgendaController@listWithDatatable')->middleware('auth:api');
            $api->POST('/datatable-tanggal','Litbang\AgendaController@listWithDatatableByTanggal')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\AgendaController@getById')->middleware('auth:api');
        });
        $api->POST('create','Litbang\AgendaController@create')->middleware('auth:api');
        $api->POST('update','Litbang\AgendaController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\AgendaController@delete')->middleware('auth:api');
    });

    /*
    |--------------------------------------------------------------------------
    | Berita
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'berita'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\BeritaController@list');
            $api->GET('/datatable','Litbang\BeritaController@listWithDatatable')->middleware('auth:api');
            $api->POST('/datatable-tanggal','Litbang\BeritaController@listWithDatatableByTanggal')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\BeritaController@getById');
        });
        $api->POST('create','Litbang\BeritaController@create')->middleware('auth:api');
        $api->POST('update','Litbang\BeritaController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\BeritaController@delete')->middleware('auth:api');
        $api->GET('terkini','Litbang\BeritaController@terkini');

        $api->POST('komentar','Litbang\BeritaController@tambahKomentar');
        $api->POST('balas-komentar','Litbang\BeritaController@balasKomentar');
        //$api->POST('delete','Litbang\BeritaController@delete')->middleware('auth:api');
    });

    /*
    |--------------------------------------------------------------------------
    | Usulan Penelitian
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'usulan-penelitian'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\UsulanPenelitianController@list');
            $api->GET('/external','Litbang\UsulanPenelitianController@listExternal');
            $api->GET('/datatable','Litbang\UsulanPenelitianController@listWithDatatable')->middleware('auth:api');
            $api->POST('/datatable-tanggal','Litbang\UsulanPenelitianController@listWithDatatableByTanggal')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\UsulanPenelitianController@getById');
        });
        $api->POST('create','Litbang\UsulanPenelitianController@create');
        $api->POST('update','Litbang\UsulanPenelitianController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\UsulanPenelitianController@delete')->middleware('auth:api');
        $api->GET('numbering','Litbang\UsulanPenelitianController@getNumbering');
        $api->GET('nomor','Litbang\UsulanPenelitianController@getAutoNomor');

    });

    /*
    |--------------------------------------------------------------------------
    | Surat
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'surat-keluar'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\SuratController@listSuratKeluar');
            $api->GET('/datatable','Litbang\SuratController@listSuratKeluarWithDatatable')->middleware('auth:api');
            $api->POST('/datatable-tanggal','Litbang\SuratController@listSuratKeluarWithDatatableByTanggal')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\SuratController@getByIdSuratKeluar')->middleware('auth:api');
        });
        $api->POST('create','Litbang\SuratController@createSuratKeluar')->middleware('auth:api');
        $api->POST('update','Litbang\SuratController@updateSuratKeluar')->middleware('auth:api');
        $api->POST('delete','Litbang\SuratController@deleteSuratKeluar')->middleware('auth:api');
        $api->GET('numbering','Litbang\UsulanPenelitianController@getNumbering');
        $api->GET('nomor','Litbang\SuratController@getAutoNomorSk');

    });
    $api->group(['prefix' => 'surat-masuk'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\SuratController@listSuratMasuk');
            $api->GET('/datatable','Litbang\SuratController@listSuratMasukWithDatatable')->middleware('auth:api');
            $api->POST('/datatable-tanggal','Litbang\SuratController@listSuratMasukWithDatatableByTanggal')->middleware('auth:api');

        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\SuratController@getByIdSuratMasuk')->middleware('auth:api');
        });
        $api->POST('create','Litbang\SuratController@createSuratMasuk')->middleware('auth:api');
        $api->POST('update','Litbang\SuratController@updateSuratMasuk')->middleware('auth:api');
        $api->POST('delete','Litbang\SuratController@deleteSuratMasuk')->middleware('auth:api');
        $api->GET('numbering','Litbang\UsulanPenelitianController@getNumbering');
        $api->GET('nomor','Litbang\SuratController@getAutoNomorSm');

    });

    $api->group(['prefix' => 'jenis-surat'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\SuratController@listJenisSurat');
            $api->GET('/datatable','Litbang\SuratController@listJenisSuratWithDatatable')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\SuratController@getByIdJenisSurat')->middleware('auth:api');
        });
        $api->POST('create','Litbang\SuratController@createJenisSurat')->middleware('auth:api');
        $api->POST('update','Litbang\SuratController@updateJenisSurat')->middleware('auth:api');
        $api->POST('delete','Litbang\SuratController@deleteJenisSurat')->middleware('auth:api');

        $api->GET('numbering','Litbang\UsulanPenelitianController@getNumbering');

    });

    /*
    |--------------------------------------------------------------------------
    | Usulan Inovasi
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'usulan-inovasi'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\UsulanInovasiController@list');
            $api->GET('/datatable','Litbang\UsulanInovasiController@listWithDatatable')->middleware('auth:api');
            $api->POST('/datatable-tanggal','Litbang\UsulanInovasiController@listWithDatatableByTanggal')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\UsulanInovasiController@getById');
        });
        $api->POST('create','Litbang\UsulanInovasiController@create');
        $api->POST('update','Litbang\UsulanInovasiController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\UsulanInovasiController@delete')->middleware('auth:api');
        $api->GET('terkini','Litbang\UsulanInovasiController@terkini');
        $api->POST('update-status','Litbang\UsulanInovasiController@updateStatus')->middleware('auth:api');
        $api->GET('nomor','Litbang\UsulanInovasiController@getAutoNomor');
    });


    /*
    |--------------------------------------------------------------------------
    | Attachment
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'attachment'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\BeritaController@list');
            $api->GET('/datatable','Litbang\BeritaController@listWithDatatable')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\BeritaController@getById')->middleware('auth:api');
        });
        $api->POST('create','Litbang\BeritaController@create');
        $api->POST('update','Litbang\BeritaController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\BeritaController@delete')->middleware('auth:api');

        $api->GET('/terkini','Litbang\AttachmentController@terkini');
        $api->GET('/foto','Litbang\AttachmentController@getFoto');
        $api->GET('/video','Litbang\AttachmentController@getVideo');
    });

    /*
    |--------------------------------------------------------------------------
    | Regulasi
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'regulasi'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\RegulasiController@list');
            $api->GET('/datatable','Litbang\RegulasiController@listWithDatatable')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\RegulasiController@getById')->middleware('auth:api');
        });
        $api->POST('create','Litbang\RegulasiController@create');
        $api->POST('update','Litbang\RegulasiController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\RegulasiController@delete')->middleware('auth:api');

        $api->GET('/terkini','Litbang\RegulasiController@terkini');
    });

    /*
    |--------------------------------------------------------------------------
    | Survey
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'survey'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\SurveyController@list');
            $api->GET('/datatable','Litbang\SurveyController@listWithDatatable')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\SurveyController@getById')->middleware('auth:api');
        });
        $api->POST('create','Litbang\SurveyController@create');
        $api->POST('update','Litbang\SurveyController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\SurveyController@delete')->middleware('auth:api');

        $api->GET('/terkini','Litbang\SurveyController@terkini');
    });

    /*
    |--------------------------------------------------------------------------
    | Auth Pelaporan
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'pelaporan'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\SurveyController@list');
            $api->GET('/datatable','Litbang\SurveyController@listWithDatatable')->middleware('auth:api');
            $api->GET('/inovasi','Litbang\PelaporanController@listInovasi')->middleware('auth:api');
            $api->GET('/penelitian','Litbang\PelaporanController@listPenelitian')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\SurveyController@getById')->middleware('auth:api');
            $api->POST('user-by-tipe','Litbang\PelaporanController@getUserByTipe')->middleware('auth:api');
        });
        $api->POST('auth','Litbang\PelaporanController@auth');
        $api->POST('add-user','Litbang\PelaporanController@addUser')->middleware('auth:api');
        $api->POST('create-inovasi','Litbang\PelaporanController@createLaporanInovasi');
        $api->POST('create-penelitian','Litbang\PelaporanController@createLaporanPenelitian');
        $api->POST('update','Litbang\SurveyController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\SurveyController@delete')->middleware('auth:api');

        $api->GET('/terkini','Litbang\SurveyController@terkini');
    });

    /*
    |--------------------------------------------------------------------------
    | Prefset
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'pref'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) { 
            $api->GET('/','Litbang\SurveyController@list');
            $api->GET('/datatable','Litbang\SurveyController@listWithDatatable')->middleware('auth:api');
            $api->GET('/inovasi','Litbang\PelaporanController@listInovasi')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->GET('/','Litbang\PrefsetController@get');
            $api->POST('user-by-tipe','Litbang\PelaporanController@getUserByTipe')->middleware('auth:api');
        });
        $api->POST('update','Litbang\PrefsetController@update')->middleware('auth:api');
    });

    /*
    |--------------------------------------------------------------------------
    | Surat Rekomendasi
    |--------------------------------------------------------------------------
    */
    $api->group(['prefix' => 'surat-rekomendasi'], function ($api) {
        $api->group(['prefix' => 'list'], function ($api) {
            $api->GET('/','Litbang\SuratRekomendasiController@list');
            $api->GET('/datatable','Litbang\SuratRekomendasiController@listWithDatatable')->middleware('auth:api');
            $api->POST('/datatable-tanggal','Litbang\SuratRekomendasiController@listWithDatatableByTanggal')->middleware('auth:api');
        });
        $api->group(['prefix' => 'get'], function ($api) {
            $api->POST('id','Litbang\SuratRekomendasiController@getById')->middleware('auth:api');
        });
        $api->POST('create','Litbang\SuratRekomendasiController@create');
        $api->POST('update','Litbang\SuratRekomendasiController@update')->middleware('auth:api');
        $api->POST('delete','Litbang\SuratRekomendasiController@delete')->middleware('auth:api');
    });


});