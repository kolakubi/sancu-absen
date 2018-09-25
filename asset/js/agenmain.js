(function(){

  ///////////////////////////////
  /////// fungsi-fungsi /////////

  // footer tetap di bawah
  function fixedBottomFooter(){
    let footer = $('#footer');
    let docHeight = $(window).height();
    let footerHeight = footer.height();
    let footerTop = footer.position().top + footerHeight;
    if (footerTop < docHeight){
      footer.css('margin-top', 10+(docHeight - footerTop) + 'px');
    }
    footer.css({'margin-bottom': 0});
  }

  // fungsi checkbox
  function checkSemuaItem(){
    let pembeliansemuacheck = $('#pembeliansemuacheck');
    let pembeliansancucheck = $('#pembeliansancucheck');
    let pembelianboncucheck = $('#pembelianboncucheck');
    let pembelianprettycheck = $('#pembelianprettycheck');
    let pembelianxtremecheck = $('#pembelianxtremecheck');

    pembeliansemuacheck.on('change', function(){
      let status = $(this).prop('checked');
      pembeliansancucheck.prop('checked', status);
      pembelianboncucheck.prop('checked', status);
      pembelianprettycheck.prop('checked', status);
      pembelianxtremecheck.prop('checked', status);
    })
  }
  // apabila check semua true
  // lalu salah satu produk ada yg unChecked
  // maka semua checked = false
  function unCheckBtnSemua(){
    let pembeliansemuacheck = $('#pembeliansemuacheck');
    let pembeliansancucheck = $('#pembeliansancucheck');
    let pembelianboncucheck = $('#pembelianboncucheck');
    let pembelianprettycheck = $('#pembelianprettycheck');
    let pembelianxtremecheck = $('#pembelianxtremecheck');

    function unChecked(){
      if(pembeliansemuacheck.prop('checked') == true){
        if(pembeliansancucheck.prop('checked') == false
        || pembelianboncucheck.prop('checked') == false
        || pembelianprettycheck.prop('checked') == false
        || pembelianxtremecheck.prop('checked') == false){
          pembeliansemuacheck.prop('checked', false);
        }
      }
    }

    pembeliansancucheck.on('click', function(){
      unChecked();
    });
    pembelianboncucheck.on('click', function(){
      unChecked();
    });
    pembelianprettycheck.on('click', function(){
      unChecked();
    });
    pembelianxtremecheck.on('click', function(){
      unChecked();
    });

  }

  // submit form pembelian
  function submitFormPembelian(){
    let btnSubmit = $('#btnsubmitpembelian');
    btnSubmit.on('click', (event) => {
      event.preventDefault();

      let sancu = $('#pembeliansancucheck').prop('checked');
      let boncu = $('#pembelianboncucheck').prop('checked');
      let pretty = $('#pembelianprettycheck').prop('checked');
      let xtreme = $('#pembelianxtremecheck').prop('checked');

      if(sancu == false && boncu == false && pretty == false && xtreme == false){
        alert("pilih salah satu item");
      }
      else{
        window.location = 'pembelian';
      }
    })
  }

  ///////////////////////////////
  ////// manggil fungsi /////////

  fixedBottomFooter();
  checkSemuaItem();
  unCheckBtnSemua();
  //submitFormPembelian();

}(jQuery))
