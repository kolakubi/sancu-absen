(function(){
  /////////////////////////////
  /////// F U N G S I /////////

  // fungsi setting datatables
  function prettyTable(tableId, tableTitle){
    // Setup - add a text input to each footer cell
    $('#'+tableId+' tfoot td').each(function(){
      let title = $(this).text();
      $(this).html('<input type="text" placeholder="cari '+title+'" />' );
      // hapus fitur search di nomor sama action
      // cari input dengan placehoder 'cari '
      if($(this).children().attr('placeholder') == 'cari '){
        //jika ktemu lansung remove
        $(this).children().remove();
      }
    });
    // DataTable
    let table = $('#'+tableId).DataTable({
      // ilangin sort dan search pada kolom
      "columnDefs": [
        {
          'targets': ['action'],
          'searchable': false,
          'sortable': false
        }
      ],
      // munculin export ke excel
      dom: 'Bfrtip',
      buttons: [
        'pdf', 'excel',
        {//ganti print title
          extend: 'print',
          title: function(){
            return tableTitle
          }
        }
      ]
    });
    // Apply the search
    table.columns().every(function(){
      var that = this;

      $('input', this.footer()).on('keyup change', function() {
        if(that.search() !== this.value){
          that
          .search(this.value)
          .draw();
        }
      });
    });
  }

  // peringatan saat menghapus data
  function peringatanSaatHapusData(){
    $('.btnhapus').on('click', function(event){
      event.preventDefault();
      let link = $(this).attr('href');
      if(confirm('Anda yakin ingin menghapus Data ini ?')){
        window.location = link;
      }
    })
  }

  // fungsi otomatis date hari ini
  function dateOtomatisHariIni(){
    let tanggal = new Date();
    let tahun = tanggal.getFullYear();
    let bulan = tanggal.getMonth()+1;
    bulan = bulan <= 9 ? '0'+bulan : bulan;
    let hari = tanggal.getDate();
    hari = hari <= 9 ? '0'+hari : hari;
    let time = tahun+'-'+bulan+'-'+hari;

    let dateInput = document.getElementById("datepembelian");
    if(dateInput){
      dateInput.defaultValue = time;
    }
  }

  // fungsi hitung jumlah Item
  function hitungJumlahItem(){
    let sancu = document.getElementById('pembeliansancu');
    let boncu = document.getElementById('pembelianboncu');
    let pretty = document.getElementById('pembelianpretty');
    let xtreme = document.getElementById('pembelianxtreme');
    let totalItem = 0;
    function getAllValue(){
      totalItem = parseInt(sancu.value.replace(/\D+/g, '')) + parseInt(boncu.value.replace(/\D+/g, '')) + parseInt(pretty.value.replace(/\D+/g, '')) + parseInt(xtreme.value.replace(/\D+/g, ''));
    }
    let jumlahItem = document.getElementById('pembelianjumlahitem');
    sancu.addEventListener('change', function(){
      getAllValue();
      jumlahItem.value = totalItem;
    });
    boncu.addEventListener('change', function(){
      getAllValue();
      jumlahItem.value = totalItem;
    });
    pretty.addEventListener('change', function(){
      getAllValue();
      jumlahItem.value = totalItem;
    });
    xtreme.addEventListener('change', function(){
      getAllValue();
      jumlahItem.value = totalItem;
    });
  }

  function jumlahPembelian(){
    let pembelianJumlah = $('#pembelianjumlah');
    let pembelianHargaSancu = $('#pembelianhargasancu');
    let pembelianHargaBoncu = $('#pembelianhargaboncu');
    let pembelianHargaPretty = $('#pembelianhargapretty');
    let pembelianHargaXtreme = $('#pembelianhargaxtreme');

    let jumlah = 0;
    function totalPembelian(){
      jumlah = parseInt(pembelianHargaSancu.val().replace(/\D+/g, '')) +
      parseInt(pembelianHargaBoncu.val().replace(/\D+/g, '')) +
      parseInt(pembelianHargaPretty.val().replace(/\D+/g, '')) +
      parseInt(pembelianHargaXtreme.val().replace(/\D+/g, ''));
    }

    pembelianHargaSancu.on('change', () => {
      totalPembelian();
      pembelianJumlah.val(jumlah);
      sisaTagihan();
      pembelianSisaTagihan.val(sisa);
    });
    pembelianHargaBoncu.on('change', () => {
      totalPembelian();
      pembelianJumlah.val(jumlah);
      sisaTagihan();
      pembelianSisaTagihan.val(sisa);
    });
    pembelianHargaPretty.on('change', () => {
      totalPembelian();
      pembelianJumlah.val(jumlah);
      sisaTagihan();
      pembelianSisaTagihan.val(sisa);
    });
    pembelianHargaXtreme.on('change', () => {
      totalPembelian();
      pembelianJumlah.val(jumlah);
      sisaTagihan();
      pembelianSisaTagihan.val(sisa);
    });

    let pembelianDibayar = $('#pembeliandibayar');
    let pembelianSisaTagihan = $('#pembeliansisatagihan');
    let sisa = 0;
    function sisaTagihan(){
      sisa = parseInt(pembelianJumlah.val()) - parseInt(pembelianDibayar.val());
    }
    pembelianJumlah.on('change', function(){
      sisaTagihan();
      pembelianSisaTagihan.val(sisa);
    })

    pembelianDibayar.on('change', function(){
      sisaTagihan();
      pembelianSisaTagihan.val(sisa);
    })

  }

  function ilanginNol(){
    // target input type number di form
    let inputForm = $('.form-group input[type=number]');

      inputForm.on('focus', function(){
        let that = $(this);
        // jika tidak ada attribut readonly
        if(!$(this).attr('readonly')){
          // jika value nya nol
          if($(this).val() == 0){
            $(this).val('');
          }
        }
      });

      inputForm.on('blur', function(){
        let val = $(this).val();
        if(val == null || val == ''){
          $(this).val(0);
        }
      })
  }

  function ajaxGetAgen(){
    let agen = $('#ajaxNamaAgen');
    let btnCari = $('#btnajaxNamaAgen');
    let delay = null;
    let body = $('body');
    let ul = $('#ulajaxNamaAgen');

    function ajaxCall(agen, btnCari){
      let val = agen.val();
      ul.children().remove();

      $.ajax({
        method: 'POST',
        dataType: 'text',
        url: "getAgenJson",
        data: {
          nama: val
        },
        success: function(data){
          data = JSON.parse(data);
          for(let i = 0; i<data.length; i++){
            ul.append("<a href='#' class='list-group-item list-group-item-action pilih-agen' data-kode-agen="+data[i]['kode_agen']+">"+data[i]['nama']+"</a>");
          }

          // regis event klik ke list yg baru dibuat
          let listAgen = $('.pilih-agen');
          listAgen.on('click', function(e){
            e.preventDefault();

            let nama = $(this).html();
            let kodeAgen = $(this).attr('data-kode-agen');

            agen.val(kodeAgen);

            ul.children().remove();
          }) // end of listAgen click

          ///////////////////////////////////////////

        },
        error: function(err1, err2, err3){
          console.log(err1);
          console.log(err2);
          console.log(err3);
        }
      }); // end of ajax
    }

    // ketik event
    agen.on('keyup', function(){

      clearTimeout(delay);
      delay = setTimeout(function(){

        ajaxCall(agen, btnCari);

      }, 500);
    }) // end of ketik event

    // click event
    btnCari.on('click', function(){
      ajaxCall(agen, btnCari);
    }) // end of btnCari click

    body.on('click', function(e){
      if(e.target.tagName == 'DIV'){
        e.stopPropagation();
        ul.children().remove();
      }
    })// end of ilangin list dgn klik body

  } // end of ajaxGetAgen


  // fungsi matiin enter
  function disableEnterDiPembelian(){
    let formPembelian = $('#formdisable');
    let input = formPembelian.children('input');

    formPembelian.on('keydown', function(event){
      let key = event.keyCode || event.which;
      if(key === 13){
        event.preventDefault();
      }
    })
  }// end of fungsi matiin enter

  // fungsi auto numeric
  function autoDecimal(){
    $('.autonum').autoNumeric('init', {vMin: '0', vMax: '999999999' });
  }

  function extractDecimal($elem){
    btnExtract.on('click', function(){
      let autoNumeric = $elem;
      let val = autoNumeric.val();
      let hanyaAngka = val.replace(/\D+/g, '');
      
      return hanyaAngka;
    })
  }
 

  ////////////////////////////////////////
  ///////// Fungsi Pembayaran ////////////

  function autoSisaTagihan(){
    let tagihanSebelumnya = $('#bayartagihansebelumnya');
    let dibayar = $('#bayardibayar');
    let sisaTagihan = $('#bayarsisatagihan');

    let selisih = 0;
    function sisaTagihanFunc(){
      selisih = parseInt(tagihanSebelumnya.val().replace(/\D+/g, '')) - parseInt(dibayar.val().replace(/\D+/g, ''));
    }

    dibayar.on('change', function(){
      sisaTagihanFunc();
      sisaTagihan.val(selisih);
    })
  }


  //////////////////////////////////////////////////
  //////////////////////////////////////////////////
  ////// Panggil Fungsi general ////////////////////

  //datatables agen
  prettyTable('datatableagen', 'Info Agen');
  //datatables pembelian
  prettyTable('datatablepembelian', 'Info Pembelian');
  //datatables pembayaran
  prettyTable('datatablepembayaran', 'Pembayaran');
  //datatables pembayaran Detail
  prettyTable('datatablepembayarandetail', 'Detail Pembayaran');
  // peringatan saat menghapus data
  peringatanSaatHapusData();
  // ilangin nol saat focus
  ilanginNol();
  // ambil ketikan data agen
  ajaxGetAgen();
  // matiin enter di form pembelian
  disableEnterDiPembelian();
  // fungsi otomatis date hari ini
  dateOtomatisHariIni();
  // fungsi auto Numeric
  autoDecimal();


  //////////////////////////////////////////////////
  //////////////////////////////////////////////////
  ////// Fungsi tambah pembelian ///////////////////

  // jika ada di halaman agen/pembelian
  let kodeHalaman = window.location.pathname.split("/").pop();
  if(window.location.pathname == "/sancu-agen/admin/pembeliantambah"){
    // fungsi otomatis date hari ini
    //dateOtomatisHariIni();
    // fungsi hitung jumlah Item
    hitungJumlahItem();
    // fungsi hitung jumlah pembelian
    jumlahPembelian();
  }
  else if(window.location.pathname == "/sancu-agen/admin/pembelianubah/"+kodeHalaman){
    // fungsi hitung jumlah Item
    hitungJumlahItem();
    // fungsi hitung jumlah pembelian
    jumlahPembelian();
  }

  //////////////////////////////////////////////////
  //////////////////////////////////////////////////
  ////// Fungsi pembayaran ///////////////////
  autoSisaTagihan();

}(jQuery))
