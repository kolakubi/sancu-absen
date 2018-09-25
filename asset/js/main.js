(function($){

	postData();

	function postData(){
		var btnSimpan = $('#btn-simpan');
		var formManusia = $('#formManusia');

		btnSimpan.on("click", function(e){
			e.preventDefault();

			$.ajax({
				type: 'POST',
				url: 'http://localhost/ci/part5/ajax/simpan',
				data: formManusia.serialize(),
				dataType: 'text',
				success: function(data){
					if(data){
						alert(data);
					}
				}
			})
		})
	}

}(jQuery))