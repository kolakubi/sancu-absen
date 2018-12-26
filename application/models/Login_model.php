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

    public function ubahEngKeInd($day){

        $hari = null;

        if($day == 'Sunday'){
            $hari = 'minggu';
        }
        elseif($day == 'Monday'){
            $hari = 'senin';
        }
        elseif($day == 'Tuesday'){
            $hari = 'selasa';
        }
        elseif($day == 'Wednesday'){
            $hari = 'rabu';
        }
        elseif($day == 'Thursday'){
            $hari = 'kamis';
        }
        elseif($day == 'Friday'){
            $hari = 'jumat';
        }
        elseif($day == 'Saturday'){
            $hari = 'sabtu';
        }

        return $hari;

    }

    public function cekHariKemarin(){
        $hariKemarin = date('l',strtotime("-1 days"));
        return $this->ubahEngKeInd($hariKemarin);

        //result 'monday'
    }

    public function cekAbsenKemarin($id_karyawan){
        $this->db->select('*');
        $this->db->from('absen');
        $this->db->where(array('id_karyawan' => $id_karyawan));
        $this->db->limit('1');
        $this->db->order_by('id_absen', 'DESC');
        return $this->db->get()->result_array()[0]['hari'];

        // result 'senin'
    }

    public function cekhariIni(){
        $hari = date('l');
        return $this->ubahEngKeInd($hari);;

        // result 'tuesday'
    }

    public function cekApakahKemarinAbsen($id_karyawan){
        $seminggu = ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];
        $kemarin = $this->cekAbsenKemarin($id_karyawan);
        $indexKe = 0;

        $hariIni = $this->cekHariIni();
        if($hariIni == 'senin'){
            if($kemarin != 'sabtu'){
                return false;
            }

            return true;
            
        }else{
            // cek index ke brp
            for($i=0; $i<count($seminggu); $i++){
                
                if($seminggu[$i] == $hariIni){
                    break;
                }
                $indexKe++;
            }

            // kemarin true
            if($kemarin == $seminggu[$indexKe-1]){
                return true;
            }
            // jika kemarin = hari ini
            // jika absen double
            if($kemarin == $hariIni){
                return true;
            }
            else{
                return false;
            }
        }

        return true;
    }

    public function insertAbsenAlpha($id_karyawan, $status){
        $bulan = date('F');
        $tahun = date('Y');
        $jam = date('H');

        // format 2018-10-15 14:08:16
        $kemarin = strtotime("yesterday $jam:00");
        $kemarin = date("Y-m-d H:i:s\n", $kemarin);

        // ambil hari
        $hari = $this->cekHariKemarin();

        // jika kemari = minggu
        // insert kemarin = sabtu
        if($hari == 'minggu'){
            $this->db->insert('absen', 
                array(
                    'id_karyawan' => $id_karyawan,
                    'waktu' => $kemarin,
                    'status' => $status.' ALPHA',
                    'hari' => 'sabtu',
                    'bulan' => $bulan,
                    'tahun' => $tahun
                )
            );
        }
        // jika kemarin != minggu
        // insert hari normal
        else{
            $this->db->insert('absen', 
                array(
                    'id_karyawan' => $id_karyawan,
                    'waktu' => $kemarin,
                    'status' => $status.' ALPHA',
                    'hari' => $hari,
                    'bulan' => $bulan,
                    'tahun' => $tahun
                )
            );
        }

        

    }

    // cek hari ini - done
    // cek hari ini di array index ke brp
    // cek index -1 (kemarin) - done
    // ambil data absen -1 - done
    // cek apakah hari absen -1 == hari -1 
    // jika ya true
    // jika tdk false


    public function login($username, $password, $ip, $status){
        // ambil data berdasarkan username dan password yg diinput
        $result = $this->db->get_where('login', array('id_karyawan' => $username, 'password' => $password));
        $num_rows = $result->num_rows();

        // jika result nya ada
        if($num_rows > 0){

            // cek apakah kemarin absen
            // jika kemarin tidak absen
            if($this->cekApakahKemarinAbsen($username) == false){
                // input absen alpha
                $this->insertAbsenAlpha($username, $status);
            }

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
