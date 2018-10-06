<?php

  class Login_Model extends CI_Model{

    public function __construct(){
      // koneksi ke database
      $this->load->database();
    }

    public function simpanAbsen($id_karyawan, $status){

        $tanggal = date('d');
        $bulan = date('F');
        $hari = date('l');
        $tahun = date('Y');

        if($hari == 'Sunday'){
            $hari = 'minggu';
        }
        elseif($hari == 'Monday'){
            $hari = 'senin';
        }
        elseif($hari == 'Tuesday'){
            $hari = 'selasa';
        }
        elseif($hari == 'Wednesday'){
            $hari = 'rabu';
        }
        elseif($hari == 'Thursday'){
            $hari = 'kamis';
        }
        elseif($hari == 'Friday'){
            $hari = 'jumat';
        }
        elseif($hari == 'Saturday'){
            $hari = 'sabtu';
        }

        $this->db->insert('absen', 
            array(
                'id_karyawan' => $id_karyawan,
                'status' => $status,
                'hari' => $hari,
                'bulan' => $bulan,
                'tahun' => $tahun
            )
        );
        
        return true;
    } // end of function simpanAbsen
 
    public function ambilDataKaryawan($id_karyawan){

        $this->db->select('*');
        $this->db->from('karyawan');
        $this->db->join('login', 'login.id_karyawan = karyawan.id_karyawan');
        $this->db->where('karyawan.id_karyawan', $id_karyawan);
        return $this->db->get()->row_array();

    } // end of function ambilDataKaryawan

    public function simpanLog($id_karyawan, $ip, $status){
        
        $this->db->insert('log', array(
            'id_karyawan' => $id_karyawan,
            'ip' => $ip,
            'status' => $status            
        ));

    }

    public function login($username, $password, $ip, $status){

        // ambil data berdasarkan usename dan password yg diinput
        $result = $this->db->get_where('login', array('id_karyawan' => $username, 'password' => $password));
        $num_rows = $result->num_rows();

        // jika result nya ada
        if($num_rows > 0){

            // simpan data absen
            $this->simpanAbsen($username, $status);

            // simpan log
            $this->simpanLog($username, $ip, 'sukses');

            // ambil data karyawan
            $dataKaryawan = $this->ambilDataKaryawan($username);

            // set session
            $_SESSION['karyawan'] = $dataKaryawan;

            return $dataKaryawan['level'];

        }

    // jika tdk ada return false
    return false;

    }

  }
