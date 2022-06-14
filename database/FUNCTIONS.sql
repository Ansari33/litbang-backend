SET GLOBAL log_bin_trust_function_creators = 1;
DELIMITER //
CREATE DEFINER=`cci`@`%` FUNCTION `cekTipe`(
	`id_ac` int
)
RETURNS varchar(30) CHARSET utf8mb4
LANGUAGE SQL
NOT DETERMINISTIC
READS SQL DATA
SQL SECURITY DEFINER
COMMENT ''
BEGIN

   DECLARE tipe_akun VARCHAR(30);

  # SET income = 0;

   SELECT tipe INTO tipe_akun FROM acc_tipe_akun WHERE id in 
	(SELECT acc_akun.tipe_akun_id FROM acc_akun WHERE id = id_ac);

   RETURN tipe_akun;

END; //
DELIMITER; 

DELIMITER //
CREATE DEFINER=`cci`@`%` FUNCTION `SaldoSebelumAkun`(
	`id_ac` INT,
	`id_jurnal` INT
)
RETURNS double
LANGUAGE SQL
NOT DETERMINISTIC
READS SQL DATA
SQL SECURITY DEFINER
COMMENT ''
BEGIN
# declaring variables;
 DECLARE tipe_akun VARCHAR(30);
 DECLARE is_parent int;
 DECLARE saldo DOUBLE;
 
 SELECT cekTipe(id_ac) INTO tipe_akun;
 SELECT cekIsParent(id_ac) INTO is_parent;
 iF is_parent = 0 THEN
   IF tipe_akun ='aktiva' OR  tipe_akun ='laba-rugi' THEN
   	RETURN (SELECT sum(debet)-SUM(kredit) FROM acc_jurnal_detail WHERE kode_akun = id_ac AND isnull(deleted_at) AND id < id_jurnal);	
	 ELSE
	   RETURN (SELECT sum(kredit)-SUM(debet) FROM acc_jurnal_detail WHERE kode_akun = id_ac AND isnull(deleted_at) AND id < id_jurnal);	
	END IF;
 ELSE 
 	IF tipe_akun ='aktiva' or tipe_akun ='laba-rugi' THEN
   	RETURN (SELECT sum(debet)-SUM(kredit) FROM acc_jurnal_detail WHERE kode_akun IN (SELECT id from acc_akun WHERE parent_id= id_ac ) AND isnull(deleted_at) AND id < id_jurnal);	
	 ELSE
	    # SELECT SUM(debet)-SUM(kredit) FROM acc_jurnal_detail WHERE kode_akun IN (SELECT id from acc_akun WHERE parent_id= 1 );
	   RETURN (SELECT sum(kredit)-SUM(debet) FROM acc_jurnal_detail WHERE kode_akun IN (SELECT id from acc_akun WHERE parent_id= id_ac ) AND isnull(deleted_at) AND id < id_jurnal);	
	END IF;
 END IF;
 	

END; //

DELIMITER;

DELIMITER //
CREATE DEFINER=`root`@`%` FUNCTION `SaldoSebelumAkun2`(
	`id_ac` INT,
	`tgl_jrnl` date
)
RETURNS double
LANGUAGE SQL
NOT DETERMINISTIC
READS SQL DATA
SQL SECURITY DEFINER
COMMENT ''
BEGIN
# declaring variables;
 DECLARE tipe_akun VARCHAR(30);
 DECLARE is_parent int;
 DECLARE saldo DOUBLE;
 
 SELECT cekTipe(id_ac) INTO tipe_akun;
 SELECT cekIsParent(id_ac) INTO is_parent;
 iF is_parent = 0 THEN
   IF tipe_akun ='aktiva' OR tipe_akun ='laba-rugi' THEN
   	RETURN (SELECT sum(acc_jurnal_detail.debet)-SUM(acc_jurnal_detail.kredit) 
		FROM acc_jurnal_detail,acc_jurnal_header 
		WHERE acc_jurnal_detail.kode_akun = id_ac 
		AND acc_jurnal_header.tanggal_jurnal < tgl_jrnl
		AND isnull(acc_jurnal_detail.deleted_at)
		AND acc_jurnal_detail.header_id = acc_jurnal_header.id
		);	
	 ELSE
	   RETURN (SELECT sum(acc_jurnal_detail.kredit)-SUM(acc_jurnal_detail.debet) 
		FROM acc_jurnal_detail,acc_jurnal_header 
		WHERE acc_jurnal_detail.kode_akun = id_ac 
		AND acc_jurnal_header.tanggal_jurnal < tgl_jrnl
		AND isnull(acc_jurnal_detail.deleted_at)
		AND acc_jurnal_detail.header_id = acc_jurnal_header.id
		);	
	END IF;
 #ELSE 
 #	IF tipe_akun ='aktiva' OR tipe_akun ='laba-rugi' THEN
   #	RETURN (SELECT sum(debet)-SUM(kredit) FROM temp_history_akun WHERE akun IN (SELECT id from acc_akun WHERE parent_id= id_ac )  AND tanggal_jurnal < tgl_jrnl);	
	 #ELSE
	    # SELECT SUM(debet)-SUM(kredit) FROM acc_jurnal_detail WHERE kode_akun IN (SELECT id from acc_akun WHERE parent_id= 1 );
	  # RETURN (SELECT sum(kredit)-SUM(debet) FROM temp_history_akun WHERE akun IN (SELECT id from acc_akun WHERE parent_id= id_ac )  AND tanggal_jurnal < tgl_jrnl);	
	#END IF;
 END IF;
 	
END; //
DELIMITER;



DELIMITER //
CREATE DEFINER=`cci`@`%` FUNCTION `cekSaldo`(
	`id_ac` int
)
RETURNS double
LANGUAGE SQL
NOT DETERMINISTIC
READS SQL DATA
SQL SECURITY DEFINER
COMMENT ''
BEGIN
# declaring variables;
 DECLARE tipe_akun VARCHAR(30);
 DECLARE is_parent int;
 DECLARE saldo DOUBLE;
 
 SELECT cekTipe(id_ac) INTO tipe_akun;
 SELECT cekIsParent(id_ac) INTO is_parent;
 iF is_parent = 0 THEN
   IF tipe_akun ='aktiva' OR tipe_akun='laba-rugi'  THEN
   	RETURN (SELECT sum(debet)-SUM(kredit) FROM acc_jurnal_detail WHERE kode_akun = id_ac AND isnull(deleted_at));	
	 ELSE
	   RETURN (SELECT sum(kredit)-SUM(debet) FROM acc_jurnal_detail WHERE kode_akun = id_ac AND isnull(deleted_at));	
	END IF;
 ELSE 
 	IF tipe_akun ='aktiva' OR tipe_akun='laba-rugi'  THEN
   	RETURN (SELECT sum(debet)-SUM(kredit) FROM acc_jurnal_detail WHERE kode_akun IN (SELECT id from acc_akun WHERE parent_id= id_ac ) AND isnull(deleted_at));	
	 ELSE
	    # SELECT SUM(debet)-SUM(kredit) FROM acc_jurnal_detail WHERE kode_akun IN (SELECT id from acc_akun WHERE parent_id= 1 );
	   RETURN (SELECT sum(kredit)-SUM(debet) FROM acc_jurnal_detail WHERE kode_akun IN (SELECT id from acc_akun WHERE parent_id= id_ac ) AND isnull(deleted_at));	
	END IF;
 END IF;
 	

END; //
DELIMITER;


DELIMITER //
CREATE DEFINER=`cci`@`%` FUNCTION `cekIsParent`(
	`id_ac` int
)
RETURNS int
LANGUAGE SQL
NOT DETERMINISTIC
READS SQL DATA
SQL SECURITY DEFINER
COMMENT ''
BEGIN

   DECLARE tipe_akun int;

  # SET income = 0;

   SELECT is_parent INTO tipe_akun FROM acc_akun WHERE id = id_ac;

   RETURN tipe_akun;

END; //
DELIMITER;

DELIMITER //

CREATE DEFINER=`root`@`localhost` FUNCTION `cekGudang`(
	`pengiriman_pembelian` INT,
	`faktur_pembelian` INT,
	`pengiriman_penjualan` INT,
	`faktur_penjualan` INT,
	`transfer_barang` INT,
	`retur_pembelian` INT,
	`retur_penjualan` int
)
RETURNS int(11)
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
	DECLARE gg INT;
	
	IF pengiriman_pembelian IS NOT NULL then
    
	 select gudang_id from pengiriman_pembelian_header WHERE id = 
	 (SELECT pengiriman_pembelian_header_id FROM pengiriman_pembelian_detail 
	 WHERE id = pengiriman_pembelian LIMIT 1) INTO gg;
	ELSEIF faktur_pembelian IS NOT NULL then
    select gudang_id from faktur_pembelian_header WHERE id = 
	 (SELECT faktur_pembelian_header_id FROM faktur_pembelian_detail 
	 WHERE id = faktur_pembelian LIMIT 1) INTO gg;
	ELSEIF pengiriman_penjualan IS NOT NULL then
    select gudang_id from pengiriman_penjualan_header WHERE id = 
	 (SELECT pengiriman_penjualan_header_id FROM pengiriman_penjualan_detail 
	 WHERE id = pengiriman_penjualan LIMIT 1) INTO gg;
	ELSEIF faktur_penjualan IS NOT NULL then
    select gudang_id from faktur_penjualan_header WHERE id = 
	 (SELECT faktur_penjualan_header_id FROM faktur_penjualan_detail 
	 WHERE id = faktur_penjualan LIMIT 1) INTO gg;
	ELSEIF transfer_barang IS NOT NULL then
    select gudang_tujuan_id from transfer_barang_header WHERE id = 
	 (SELECT transfer_barang_header_id FROM transfer_barang_detail 
	 WHERE id = transfer_barang LIMIT 1) INTO gg;
	ELSEIF retur_pembelian IS NOT NULL then
    select gudang_id from retur_pembelian_header WHERE id = 
	 (SELECT retur_pembelian_header_id FROM retur_pembelian_detail 
	 WHERE id = retur_pembelian LIMIT 1) INTO gg;
	ELSEIF retur_penjualan IS NOT NULL then
    select gudang_id from retur_penjualan_header WHERE id = 
	 (SELECT retur_penjualan_header_id FROM retur_penjualan_detail 
	 WHERE id = retur_penjualan LIMIT 1) INTO gg;
 
	END if;
	
	RETURN gg;
  	
END; //
DELIMITER;

DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `cekStatus`(
	`barang` INT,
	`sn` VARCHAR(100)
)
RETURNS int(11)
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
	DECLARE a INT;
	DECLARE b INT;
	DECLARE c INT;
	DECLARE d INT;
	DECLARE e INT;
	DECLARE f INT;
	
SELECT COUNT(*) AS penerimaan_pembelian FROM view_mutasi_sn WHERE harga_jasa_id = barang 
AND nomor_seri = sn AND  penerimaan_pembelian_detail_id IS NOT NULL INTO a;

SELECT COUNT(*) AS faktur_pembelian FROM view_mutasi_sn WHERE harga_jasa_id = barang 
AND nomor_seri = sn AND  faktur_pembelian_detail_id IS NOT NULL INTO b;

SELECT COUNT(*) FROM view_mutasi_sn WHERE harga_jasa_id = barang 
AND nomor_seri = sn AND  pengiriman_penjualan_detail_id IS NOT NULL INTO c;

SELECT COUNT(*) FROM view_mutasi_sn WHERE harga_jasa_id = barang 
AND nomor_seri = sn AND  faktur_penjualan_detail_id IS NOT NULL INTO d;

SELECT COUNT(*) FROM view_mutasi_sn WHERE harga_jasa_id = barang 
AND nomor_seri = sn AND  retur_pembelian_detail_id IS NOT NULL INTO e;

SELECT COUNT(*) FROM view_mutasi_sn WHERE harga_jasa_id = barang 
AND nomor_seri = sn AND  retur_penjualan_detail_id IS NOT NULL INTO f;

	RETURN a+b+f-c-d-e;
  	
END; //
DELIMITER; 

DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `getGudangAkhir`(
	`barang` INT,
	`sn` VARCHAR(100)
)
RETURNS int(11)
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
	DECLARE g INT;
	
SELECT gudang_id FROM view_mutasi_sn 
WHERE harga_jasa_id = barang 
AND nomor_seri = sn
AND ISNULL(pengiriman_penjualan_detail_id)
AND ISNULL(faktur_penjualan_detail_id)
ORDER BY tanggal DESC ,created_at desc LIMIT 1 INTO g;

	RETURN g;
  	
END; //
DELIMITER;

DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `getBarangReturPembelian`(
	`pengiriman` INT,
	`faktur` int
)
RETURNS int(11)
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
	DECLARE g INT;
	
IF pengiriman IS NOT NULL then
    select harga_jasa_id from pengiriman_pembelian_detail WHERE id = pengiriman LIMIT 1 INTO g;
ELSEIF faktur IS NOT NULL then
     select harga_jasa_id from faktur_pembelian_detail WHERE id = faktur LIMIT 1 INTO g;
 
END if;

	RETURN g;
  	
END; //
DELIMITER;

DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `getBarangReturPenjualan`(
	`pengiriman` INT,
	`faktur` int
)
RETURNS int(11)
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
	DECLARE g INT;
	
IF pengiriman IS NOT NULL then
    select harga_jasa_id from pengiriman_penjualan_detail WHERE id = pengiriman LIMIT 1 INTO g;
ELSEIF faktur IS NOT NULL then
     select harga_jasa_id from faktur_penjualan_detail WHERE id = faktur LIMIT 1 INTO g;
 
END if;

	RETURN g;
  	
END; //
DELIMITER;


DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `cekBarang`(
	`pengiriman_pembelian` INT,
	`faktur_pembelian` INT,
	`pengiriman_penjualan` INT,
	`faktur_penjualan` INT,
	`transfer_barang` INT,
	`retur_pembelian` INT,
	`retur_penjualan` int
)
RETURNS int(11)
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
	DECLARE gg INT;
	DECLARE harga_jasa INT;
	DECLARE faktur int;
	DECLARE pengiriman INT;
	
	IF pengiriman_pembelian IS NOT NULL then 
	 SELECT harga_jasa_id FROM pengiriman_pembelian_detail 
	 WHERE id = pengiriman_pembelian  INTO gg;
	ELSEIF faktur_pembelian IS NOT NULL then
   
	 SELECT harga_jasa_id FROM faktur_pembelian_detail 
	 WHERE id = faktur_pembelian  INTO gg;
	ELSEIF pengiriman_penjualan IS NOT NULL then
    SELECT harga_jasa_id FROM pengiriman_penjualan_detail 
	 WHERE id = pengiriman_penjualan INTO gg;
	ELSEIF faktur_penjualan IS NOT NULL then
    SELECT harga_jasa_id FROM faktur_penjualan_detail 
	 WHERE id = faktur_penjualan INTO gg;
	ELSEIF transfer_barang IS NOT NULL then
    SELECT harga_jasa_id FROM transfer_barang_detail 
	 WHERE id = transfer_barang INTO gg;
	ELSEIF retur_pembelian IS NOT NULL then
	 
	SELECT faktur_pembelian_detail_id FROM retur_pembelian_detail 
	 WHERE id = retur_pembelian INTO faktur;
	SELECT pengiriman_pembelian_detail_id FROM retur_pembelian_detail 
	 WHERE id = retur_pembelian INTO pengiriman;
	 if faktur IS NOT NULL then 
    	SELECT harga_jasa_id FROM faktur_pembelian_detail 
	 	WHERE id = faktur INTO gg;
	 ELSEIF pengiriman IS NOT NULL then
	 	SELECT harga_jasa_id FROM pengiriman_pembelian_detail 
	 	WHERE id = pengiriman INTO gg;
	 END if; 
	ELSEIF retur_penjualan IS NOT NULL then
	SELECT faktur_penjualan_detail_id FROM retur_penjualan_detail 
	 WHERE id = retur_penjualan INTO faktur;
    SELECT harga_jasa_id FROM faktur_penjualan_detail 
	 WHERE id = faktur INTO gg;
 
	END if;
	
	RETURN gg;
  	
END; //
DELIMITER;

DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `getTanggal`(
	`pengiriman_pembelian` INT,
	`faktur_pembelian` INT,
	`pengiriman_penjualan` INT,
	`faktur_penjualan` INT,
	`transfer_barang` INT,
	`retur_pembelian` INT,
	`retur_penjualan` INT
)
RETURNS date
LANGUAGE SQL
NOT DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
	DECLARE gg date;
	
	IF pengiriman_pembelian IS NOT NULL then
	 select tanggal from pengiriman_pembelian_header WHERE id = 
	 (SELECT pengiriman_pembelian_header_id FROM pengiriman_pembelian_detail 
	 WHERE id = pengiriman_pembelian LIMIT 1) INTO gg;
	ELSEIF faktur_pembelian IS NOT NULL then
    select tanggal from faktur_pembelian_header WHERE id = 
	 (SELECT faktur_pembelian_header_id FROM faktur_pembelian_detail 
	 WHERE id = faktur_pembelian LIMIT 1) INTO gg;
	ELSEIF pengiriman_penjualan IS NOT NULL then
    select tanggal from pengiriman_penjualan_header WHERE id = 
	 (SELECT pengiriman_penjualan_header_id FROM pengiriman_penjualan_detail 
	 WHERE id = pengiriman_penjualan LIMIT 1) INTO gg;
	ELSEIF faktur_penjualan IS NOT NULL then
    select tanggal from faktur_penjualan_header WHERE id = 
	 (SELECT faktur_penjualan_header_id FROM faktur_penjualan_detail 
	 WHERE id = faktur_penjualan LIMIT 1) INTO gg;
	ELSEIF transfer_barang IS NOT NULL then
    select tanggal from transfer_barang_header WHERE id = 
	 (SELECT transfer_barang_header_id FROM transfer_barang_detail 
	 WHERE id = transfer_barang LIMIT 1) INTO gg;
	ELSEIF retur_pembelian IS NOT NULL then
    select tanggal from retur_pembelian_header WHERE id = 
	 (SELECT retur_pembelian_header_id FROM retur_pembelian_detail 
	 WHERE id = retur_pembelian LIMIT 1) INTO gg;
	ELSEIF retur_penjualan IS NOT NULL then
    select tanggal from retur_penjualan_header WHERE id = 
	 (SELECT retur_penjualan_header_id FROM retur_penjualan_detail 
	 WHERE id = retur_penjualan LIMIT 1) INTO gg;
 
	END if;
	
	RETURN gg;
  	
END; //
DELIMITER;

DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `SaldoPerTransaksiAkun`(
	`id_ac` INT,
	`saldoSebelum` DOUBLE,
	`kredit` DOUBLE,
	`debet` DOUBLE,
	`id_jurnal` INT
)
RETURNS double
LANGUAGE SQL
NOT DETERMINISTIC
READS SQL DATA
SQL SECURITY DEFINER
COMMENT ''
BEGIN
# declaring variables;
 DECLARE tipe_akun VARCHAR(30);
 DECLARE is_parent int;
 DECLARE saldo DOUBLE;
 
 SELECT cekTipe(id_ac) INTO tipe_akun;
 SELECT cekIsParent(id_ac) INTO is_parent;
 iF is_parent = 0 THEN
   IF tipe_akun ='aktiva' or tipe_akun ='laba-rugi'  THEN
   	RETURN ifnull(saldoSebelum,0) + (debet-kredit);
	 ELSE
	   RETURN ifnull(saldoSebelum,0) + (kredit-debet);	
	END IF;
 ELSE 
 	IF tipe_akun ='aktiva' OR tipe_akun ='laba-rugi'  THEN
   	RETURN ifnull(saldoSebelum,0) + (SELECT sum(debet)-SUM(kredit) FROM acc_jurnal_detail WHERE kode_akun IN (SELECT id from acc_akun WHERE parent_id= id_ac ) AND isnull(deleted_at) AND id < id_jurnal);	
	 ELSE
	    # SELECT SUM(debet)-SUM(kredit) FROM acc_jurnal_detail WHERE kode_akun IN (SELECT id from acc_akun WHERE parent_id= 1 );
	   RETURN ifnull(saldoSebelum,0) + (SELECT sum(kredit)-SUM(debet) FROM acc_jurnal_detail WHERE kode_akun IN (SELECT id from acc_akun WHERE parent_id= id_ac ) AND isnull(deleted_at) AND id < id_jurnal);	
	END IF;
 END IF;
 	
END; //
DELIMITER;

DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `total_debet_sebelum`(
	`kode` INT,
	`tanggal` DATE,
	`id_detail` INT
)
RETURNS int(11)
LANGUAGE SQL
DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
DECLARE jml INT;
SELECT ifnull(sum(debet),0) AS total_debet INTO jml FROM view_kas_bank WHERE kode_akun = kode AND  tanggal_jurnal < tanggal  AND  jurnal_detail_id < id_detail;
RETURN jml;
END; //
DELIMITER;

DELIMITER //
CREATE DEFINER=`root`@`localhost` FUNCTION `total_kredit_sebelum`(
	`kode` INT,
	`tanggal` DATE,
	`id_detail` INT
)
RETURNS int(11)
LANGUAGE SQL
DETERMINISTIC
CONTAINS SQL
SQL SECURITY DEFINER
COMMENT ''
BEGIN
DECLARE jml INT;
SELECT ifnull(sum(kredit),0) AS total_kredit INTO jml FROM view_kas_bank WHERE kode_akun = kode AND  tanggal_jurnal < tanggal  AND  jurnal_detail_id < id_detail;
RETURN jml;
END; //
DELIMITER;