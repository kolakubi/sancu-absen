<!-- Array
(
    [__ci_last_regenerate] => 1537869658
    [jenisabsen] => in
    [karyawan] => Array
        (
            [id_karyawan] => erna
            [nama] => Erna Sancu
            [alamat] => 
            [no_telepon] => 
            [jabatan] => Pajak
            [foto] => foto1.jpg
            [password] => 1234
            [level] => 1
        )

) -->

<!-- <pre>
<?php print_r($_SESSION) ?>
</pre> -->

<?php
    $tanggal = date('d');
    $bulan = date('F');
    $hari = date('l');
    $tahun = date('Y');
    $waktu = date('G:i:s');
    $jam = date('G');
    $menit = date('i');

    if($hari == 'Sunday'){
        $hari = 'Minggu';
    }
    elseif($hari == 'Monday'){
        $hari = 'Senin';
    }
    elseif($hari == 'Tuesday'){
        $hari = 'Selasa';
    }
    elseif($hari == 'Wednesday'){
        $hari = 'Rabu';
    }
    elseif($hari == 'Thursday'){
        $hari = 'Kamis';
    }
    elseif($hari == 'Friday'){
        $hari = 'Jumat';
    }
    elseif($hari == 'Saturday'){
        $hari = 'Sabtu';
    }
?>

<div class="row">
    <div class="col-xs-12">

        <!-- gambar -->
        <p class="text-center">
            <img src="asset/foto/<?php echo $_SESSION['karyawan']['foto'] ?>" alt="<?php echo $_SESSION['karyawan']['nama'] ?>" class="img img-responsive img-circle" style="max-width: 300px; margin: 0 auto;">
        </p>

        <!-- greeting -->
        <h1 class="text-center" style="color: black;">
            
            <?php if($_SESSION['jenisabsen'] == 'in'){echo 'Selamat Pagi,';}elseif($_SESSION['jenisabsen'] == 'out'){echo 'Selamat Sore,';} ?>
            <?php echo $_SESSION['karyawan']['nama'] ?>
        </h1>
        
        <?php if($_SESSION['jenisabsen'] == 'in') : ?>
            <h2 class="text-center">Selamat Bekerja</h2>
        <?php elseif($_SESSION['jenisabsen'] == 'out') : ?>
            <h2 class="text-center">Hati2 di jalan...</h2>
        <?php endif ?>

        <!-- waktu -->
        <h3 class="text-center">
            <?php echo $hari.', '.$tanggal.' '.$bulan.' '.$tahun ?>
        </h3>
        <h1 class="text-center" style="font-size: 70px; font-weight: bold; color: green">
            <?php echo $waktu ?>
        </h1>

        <?php if($_SESSION['jenisabsen'] == 'in') : ?>
            <?php if($jam < 8): ?>
                <?php if($menit < 31) : ?>
                    <h3 class="text-center text-success" style="font-weight: bold;">kamu tidak terlambat</h3>
                <?php elseif($menit > 30) : ?>
                    <h3 class="text-center text-danger" style="font-weight: bold;">kamu terlambat</h3>
                <?php endif ?>
            <?php else: ?>
                <h3 class="text-center text-danger" style="font-weight: bold;">kamu terlambat</h3>
            <?php endif ?>
        <?php endif ?>

        <!-- tombol kembali -->
        <p class="text-center">
            <a href="<?php echo base_url() ?>logout" class="btn btn-info btn-lg" style="width: 100px;">ok</a>
        </p>
    </div>
</div>
