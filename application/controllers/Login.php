<?php

    class Login extends CI_Controller{

        public function __construct(){

            parent::__construct();

        }

        public function jenis($jenisAbsen=null){
            
            if(!$jenisAbsen){
                redirect('menuabsen');
            }

            // set session in
            if($jenisAbsen == 1){
                $_SESSION['jenisabsen'] = "in";
            }
            
            // set session out
            if($jenisAbsen == 2){
                $_SESSION['jenisabsen'] = "out";
            }

            $data['gagal'] = false;

            $this->load->view('login/login', $data);

		} // end of login
		
		public function cekIp($ip){

			// ipKantor
			$ipKantor = array('114', '139', '111');

			// ambil 3 digit paling depan
			$ip = substr($ip, 0, 3);

			// jika bukan ipKantor
			for($i=0; $i < count($ipKantor); $i++){
				if($ip == $ipKantor[$i]){
					return true;
				}
			}
	
			return false;
	
		} // end of function cekIp

		public function ambilIP(){

			$ip = '';
			// jika di live server
			if(!empty($_SERVER['HTTP_CLIENT_IP'])){
				function get_visitor_ip() {
					$ip = '';
					if ($_SERVER['HTTP_CLIENT_IP'])
						$ip = $_SERVER['HTTP_CLIENT_IP'];
					else if($_SERVER['HTTP_X_FORWARDED_FOR'])
						$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
					else if($_SERVER['HTTP_X_FORWARDED'])
						$ip = $_SERVER['HTTP_X_FORWARDED'];
					else if($_SERVER['HTTP_FORWARDED_FOR'])
						$ip = $_SERVER['HTTP_FORWARDED_FOR'];
					else if($_SERVER['HTTP_FORWARDED'])
						$ip = $_SERVER['HTTP_FORWARDED'];
					else if($_SERVER['REMOTE_ADDR'])
						$ip = $_SERVER['REMOTE_ADDR'];
					else
						$ip = 'none';

					$ip = $ip;
				}
			}
			else{
				// jika di local server
				function get_client_ip_env() {
					$ipaddress = '';
					if (getenv('HTTP_CLIENT_IP'))
						$ipaddress = getenv('HTTP_CLIENT_IP');
					else if(getenv('HTTP_X_FORWARDED_FOR'))
						$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
					else if(getenv('HTTP_X_FORWARDED'))
						$ipaddress = getenv('HTTP_X_FORWARDED');
					else if(getenv('HTTP_FORWARDED_FOR'))
						$ipaddress = getenv('HTTP_FORWARDED_FOR');
					else if(getenv('HTTP_FORWARDED'))
						$ipaddress = getenv('HTTP_FORWARDED');
					else if(getenv('REMOTE_ADDR'))
						$ipaddress = getenv('REMOTE_ADDR');
					else
						$ipaddress = 'UNKNOWN';

						$ip = $ipaddress;
				}
				
			} // end of ambil ip address

			return $ip;
		} // end of ambil IP

        public function validasi(){

            // jenis absen in/out
            $status = $_SESSION['jenisabsen'];

			// set form validation
			$this->form_validation->set_rules(array(
				array(
					'field' => 'username',
					'label' => 'Username',
					'rules' => 'required'
				),
				array(
					'field' => 'password',
					'label' => 'Password',
					'rules' => 'required'
				)
			));

			$this->form_validation->set_message('required', '%s tidak boleh kosong');

			if(!$this->form_validation->run()){
				$data['gagal'] = false;
				$this->load->view('login/login', $data);
			}
			else{
				// ambil value dr field
				$username = $this->input->post('username');
				$password = $this->input->post('password');

				// ambil ip dari fungsi ambilIP()
				$ip = $this->ambilIP();

				// cek ip
				// if(!$this->cekIp($ip)){
				//     redirect('ipsalah');
				// }

				//ambil data dari model login_model
				$result = $this->login_model->login($username, $password, $ip, $status);
				// jika ada result
				if($result){

					$level = $result['level']; // cek level
					$this->session->set_userdata($result);

					if($level === '1'){ // jika level 1 ke admin
						redirect('karyawan');
					}
					else if($level === '2'){
						$_SESSION['level'] = '2';
						redirect('admin'); // jika level 2 ke agen
					}
					else{
						return false;
					}
				}
				// jika tdk ada result
				else{
					$data['gagal'] = true;
					$this->load->view('login/login', $data); // kembali ke login
				}
			}
		} // end of validasi

    }