<?php namespace App\Helpers;

class MessageConstant
{
	const RESPONSE_200 = 'OK';
	const RESPONSE_201 = 'Resource Created';
	const RESPONSE_400 = 'Bad Request';
	const RESPONSE_401 = 'Not Authorized';
	const RESPONSE_404 = 'Resource Not Found';
	const RESPONSE_409 = 'Conflicted Resource';
	const RESPONSE_500 = 'Internal Server Error';

	const VALIDATION_FAILED_MSG = 'Validasi Data Gagal';
	const VALIDATION_REQUIRED_MSG = 'Wajib Diisi!';
	const QUANTITY_VALIDATION_MSG = 'Kuantitas Tidak Sama Dengan Transaksi Sebelumnya !';
	const SN_VALIDATION_MSG = 'Nomor Seri Telah Ada / Barang Menggunakan Nomor Seri, Silahkan Memasukkan Nomor Seri !';
	const LOGIN_FAILED_MSG = 'Login Gagal';
	const ZERO_QUANTITY_MSG = 'Barang Tidak Dapat Di Jual, Tidak Memiliki Stok!';
	const LESS_QUANTITY_MSG = 'Jumlah Penjualan Melebihi Stok Pada Gudang!';
	const SN_NOT_FOUND_MSG = 'SN Stok Tidak Ada Pada Gudang Yang Dipilih!';
	const SN_UNAVAILABLE_MSG = 'SN Sudah Tidak Tersedia!';

	/*
	|--------------------------------------------------------------------------
	| Kelitbangan
	|--------------------------------------------------------------------------
	*/
	const KELITBANGAN_CREATE_SUCCESS_MSG = 'Kelitbangan berhasil dibuat !';
	const KELITBANGAN_FAILED_MSG = "Kelitbangan tidak dapat dibuat, silakan hubungi administrator !";
	const KELITBANGAN_GET_FAILED_MSG = "Kelitbangan tidak ditemukan";
	const KELITBANGAN_UPDATE_REQUEST_FAILED_MSG = "Kelitbangan tidak dapat diambil, harap hubungi administrator !";
	const KELITBANGAN_UPDATE_SUCCESS_MSG = 'Kelitbangan berhasil diperbarui !';
	const KELITBANGAN_UPDATE_FAILED_MSG = "Kelitbangan tidak dapat diperbarui silakan hubungi administrator !";
	const KELITBANGAN_DELETE_SUCCESS_MSG = 'Kelitbangan berhasil dihapus !';
	const KELITBANGAN_DELETE_FAILED_MSG = "Kelitbangan tidak dapat dihapus, harap hubungi administrator !";

    /*
    |--------------------------------------------------------------------------
    | Inovasi
    |--------------------------------------------------------------------------
    */
    const INOVASI_CREATE_SUCCESS_MSG = 'Inovasi berhasil dibuat !';
    const INOVASI_CREATE_FAILED_MSG = "Inovasi tidak dapat dibuat, silakan hubungi administrator !";
    const INOVASI_GET_FAILED_MSG = "Inovasi tidak ditemukan";
    const INOVASI_UPDATE_REQUEST_FAILED_MSG = "Inovasi tidak dapat diambil, harap hubungi administrator !";
    const INOVASI_UPDATE_SUCCESS_MSG = 'Inovasi berhasil diperbarui !';
    const INOVASI_UPDATE_FAILED_MSG = "Inovasi tidak dapat diperbarui silakan hubungi administrator !";
    const INOVASI_DELETE_SUCCESS_MSG = 'Inovasi berhasil dihapus !';
    const INOVASI_DELETE_FAILED_MSG = "Inovasi tidak dapat dihapus, harap hubungi administrator !";

    /*
    |--------------------------------------------------------------------------
    | Instansi
    |--------------------------------------------------------------------------
    */
    const INSTANSI_CREATE_SUCCESS_MSG = 'Instansi berhasil dibuat !';
    const INSTANSI_CREATE_FAILED_MSG = "Instansi tidak dapat dibuat, silakan hubungi administrator !";
    const INSTANSI_GET_FAILED_MSG = "Instansi tidak ditemukan";
    const INSTANSI_UPDATE_REQUEST_FAILED_MSG = "Instansi tidak dapat diambil, harap hubungi administrator !";
    const INSTANSI_UPDATE_SUCCESS_MSG = 'Instansi berhasil diperbarui !';
    const INSTANSI_UPDATE_FAILED_MSG = "Instansi tidak dapat diperbarui silakan hubungi administrator !";
    const INSTANSI_DELETE_SUCCESS_MSG = 'Instansi berhasil dihapus !';
    const INSTANSI_DELETE_FAILED_MSG = "Instansi tidak dapat dihapus, harap hubungi administrator !";

    /*
    |--------------------------------------------------------------------------
    | Agenda
    |--------------------------------------------------------------------------
    */
    const AGENDA_CREATE_SUCCESS_MSG = 'Agenda berhasil dibuat !';
    const AGENDA_CREATE_FAILED_MSG = "Agenda tidak dapat dibuat, silakan hubungi administrator !";
    const AGENDA_GET_FAILED_MSG = "Agenda tidak ditemukan";
    const AGENDA_UPDATE_REQUEST_FAILED_MSG = "Agenda tidak dapat diambil, harap hubungi administrator !";
    const AGENDA_UPDATE_SUCCESS_MSG = 'Agenda berhasil diperbarui !';
    const AGENDA_UPDATE_FAILED_MSG = "Agenda tidak dapat diperbarui silakan hubungi administrator !";
    const AGENDA_DELETE_SUCCESS_MSG = 'Agenda berhasil dihapus !';
    const AGENDA_DELETE_FAILED_MSG = "Agenda tidak dapat dihapus, harap hubungi administrator !";

    /*
    |--------------------------------------------------------------------------
    | Berita
    |--------------------------------------------------------------------------
    */
    const BERITA_CREATE_SUCCESS_MSG = 'Berita berhasil dibuat !';
    const BERITA_CREATE_FAILED_MSG = "Berita tidak dapat dibuat, silakan hubungi administrator !";
    const BERITA_GET_FAILED_MSG = "Berita tidak ditemukan";
    const BERITA_UPDATE_REQUEST_FAILED_MSG = "Berita tidak dapat diambil, harap hubungi administrator !";
    const BERITA_UPDATE_SUCCESS_MSG = 'Berita berhasil diperbarui !';
    const BERITA_UPDATE_FAILED_MSG = "Berita tidak dapat diperbarui silakan hubungi administrator !";
    const BERITA_DELETE_SUCCESS_MSG = 'Berita berhasil dihapus !';
    const BERITA_DELETE_FAILED_MSG = "Berita tidak dapat dihapus, harap hubungi administrator !";


    /*
    |--------------------------------------------------------------------------
    | Us
    |--------------------------------------------------------------------------
    */
    const AGENDA_CREATE_SUCCESS_MSG = 'Agenda berhasil dibuat !';
    const AGENDA_CREATE_FAILED_MSG = "Agenda tidak dapat dibuat, silakan hubungi administrator !";
    const AGENDA_GET_FAILED_MSG = "Agenda tidak ditemukan";
    const AGENDA_UPDATE_REQUEST_FAILED_MSG = "Agenda tidak dapat diambil, harap hubungi administrator !";
    const AGENDA_UPDATE_SUCCESS_MSG = 'Agenda berhasil diperbarui !';
    const AGENDA_UPDATE_FAILED_MSG = "Agenda tidak dapat diperbarui silakan hubungi administrator !";
    const AGENDA_DELETE_SUCCESS_MSG = 'Agenda berhasil dihapus !';
    const AGENDA_DELETE_FAILED_MSG = "Agenda tidak dapat dihapus, harap hubungi administrator !";

    /*
    |--------------------------------------------------------------------------
    | Berita
    |--------------------------------------------------------------------------
    */
    const BERITA_CREATE_SUCCESS_MSG = 'Berita berhasil dibuat !';
    const BERITA_CREATE_FAILED_MSG = "Berita tidak dapat dibuat, silakan hubungi administrator !";
    const BERITA_GET_FAILED_MSG = "Berita tidak ditemukan";
    const BERITA_UPDATE_REQUEST_FAILED_MSG = "Berita tidak dapat diambil, harap hubungi administrator !";
    const BERITA_UPDATE_SUCCESS_MSG = 'Berita berhasil diperbarui !';
    const BERITA_UPDATE_FAILED_MSG = "Berita tidak dapat diperbarui silakan hubungi administrator !";
    const BERITA_DELETE_SUCCESS_MSG = 'Berita berhasil dihapus !';
    const BERITA_DELETE_FAILED_MSG = "Berita tidak dapat dihapus, harap hubungi administrator !";



}
