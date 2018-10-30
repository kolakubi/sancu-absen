<?php

    Class Admin_model extends CI_Model{

        public function __construct(){

            parent::__construct();

        }

        public function getDataKaryawanJson($nama=null){
        // ambil data nama karyawan
            $this->db->select('id_karyawan, nama');
            $this->db->from('karyawan');
            $this->db->like('nama', $nama);
            
            $result = $this->db->get()->result_array();
            return $result;
        }

        public function getDataAbsenJoin($dataAmbil=null){
            // ambil data absen join karyawan
          if(!empty($dataAmbil)){
            // jika ada id_karyawan, ambil data menurut id_karyawan
            $this->db->select('*');
            $this->db->from('absen');
            $this->db->join('karyawan', 'karyawan.id_karyawan = absen.id_karyawan');
            $this->db->where('absen.id_karyawan', $dataAmbil['id_karyawan']);
            $this->db->where('absen.waktu >=', $dataAmbil['dari']);
            $this->db->where('absen.waktu <=', $dataAmbil['sampai']);
            $this->db->like('absen.status', $dataAmbil['status']);
            $result = $this->db->get()->result_array();
            return $result;
          }
          else{
            // jika tidak ada id_karyawan, ambil semua data agen
            $this->db->select('*');
            $this->db->from('absen');
            $this->db->join('karyawan', 'karyawan.id_karyawan = absen.id_karyawan');
            $result = $this->db->get()->result_array();
            return $result;
          }
        }

    }