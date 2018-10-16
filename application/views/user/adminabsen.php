<?php

    $jam = null;
    $menit = null;
    $status = null;
    $jumlahTerlambat = 0;

?>


<h1 class="text-center text-uppercase">Laporan Absen</h1>

<div class="row">
  <div class="col-xs-12 col-md-4 col-md-offset-4">

    <?php echo form_open() ?>

        <!-- nama karyawan -->
      <div class="form-group">
        <label>Nama Karyawan:</label>
        <div class="input-group">
          <input type="text" value="" class="form-control" name="id_karyawan" id="ajaxNamaAgen">
          <span class="input-group-addon" style="cursor: pointer;" id="btnajaxNamaAgen"><i class="glyphicon glyphicon-search"></i></span>
        </div>
        <div class="list-group" id="ulajaxNamaAgen">
          <!-- <li class="list-group-item"></li> -->
        </div>
        <span class="text-danger"><?php echo form_error('id_karyawan') ?></span>
      </div>

      <!-- status absen in/out -->
        <div class="form-group">
            <label>Status: </label>
            <select name="status" class="form-control">
                <option value="in">In</option>
                <option value="out">Out</option>
            </select>
            <span class="text-danger"><?php echo form_error('status') ?></span>
        </div>

      <!-- tanggal dari -->
      <div class="form-group">
        <label>Dari: </label>
        <input type="date" name="tanggaldari" class="form-control">
        <span class="text-danger"><?php echo form_error('tanggaldari') ?></span>
      </div>

      <!-- tanggal sampai -->
      <div class="form-group">
        <label>Sampai: </label>
        <input type="date" name="tanggalsampai" class="form-control" id="datepembelian">
        <span class="text-danger"><?php echo form_error('tanggalsampai') ?></span>
      </div>

      <!-- button submit -->
      <div class="form-group">
        <button type="submit" class="btn btn-info btn-block">Cari</button>
      </div>
    <?php echo form_close() ?>

  </div>
</div>

<!-- info tanggal pencarian -->
<?php if(!empty($tanggal)) : ?>
  <div class="well" style="margin-bottom: -20px;">
    <p class="text-center"><strong>Menampilkan pencarian tanggal</strong></p>
    <p style="font-size: 18px; margin-top: -10px;" class="text-center text-success"><strong><?php echo $tanggal['dari']?> <span style="color: #555555">s/d</span> <?php echo $tanggal['sampai']?></strong></p>
  </div>
<?php endif ?>

<br><br>

<table class="table table-striped table-bordered table-hover table-condensed table-responsive" id="datatablepembelian">
  <thead>
    <tr class="info">
      <th>No</th>
      <th>Nama Karyawan</th>
      <th>Tanggal</th>
      <th>Absen</th>
      <th>Status</th>
      <!-- <th>Action</th> -->
      <th>Hari</th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 0; ?>
    <?php foreach ($absen as $absenDetail) : ?>

        <?php 
            // ambil waktu
            $time = strtotime($absenDetail['waktu']);
            $jam =  date('H', $time);
            $menit = date('i', $time);

            // logic terlambat
            if($absenDetail['status'] == 'in'){
              // jika bukan hari sabtu
              if($absenDetail['hari'] != 'sabtu'){
                if($jam < 8){
                  $status = 'tidak terlambat';
              
                  if($menit < 31){
                      $status = 'tidak terlambat';
                  } 
                      
                  elseif($menit > 30){
                      $status = 'terlambat';
                      $jumlahTerlambat += 1;
                  }
                }    
                else{
                    $status = 'terlambat';
                    $jumlahTerlambat += 1;
                }
              }
              // jika hari sabtu
              else{
                if($jam > 7){
                  if($menit > 0){
                    $status = 'terlambat';
                  }
                }else{
                  $status = 'tidak terlambat';
                }
              }
            }
        ?>

      <tr class="
        <?php
            if($absenDetail['status'] == 'in'){
                if($status == 'terlambat'){
                    echo 'danger';
                }
            }
        ?>">
        <td><?php echo $i+=1;?></td>
        <td><?php echo $absenDetail['nama']?></td>
        <td><?php echo $absenDetail['waktu'] ?></td>
        <td><?php echo $absenDetail['status']?></td>
        <td>
            <?php
                if($absenDetail['status'] == 'in'){
                    if($status == 'terlambat'){
                        echo 'terlambat';
                    }
                    else{
                        echo 'tidak terlambat';
                    }
                }
            ?>
        </td>
        <!-- button action -->
        <!-- <td> -->
            <!-- tombol ubah -->
            <!-- <a href="#" class="btn btn-warning">Ubah</a> -->

            <!-- tombol hapus -->
            <!-- <a href="#" class="btn btn-warning">Hapus</a> -->
        <!-- </td> -->

        <td><?php echo $absenDetail['hari']?></td>

      </tr>
    <?php endforeach ?>
  </tbody>
  <tfoot>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
  </tfoot>
</table>

<!-- jumlah terlambat -->
<!-- jika status = in -->
<?php if(!empty($absen[0]['status'])) : ?>
  <?php if($absen[0]['status'] == 'in') : ?>
    <h3 class="text-center text-danger">Jumlah Terlambat = <?php echo $jumlahTerlambat ?></h3>
  <?php endif ?>
<?php endif ?>