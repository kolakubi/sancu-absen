<?php

    Class Admin Extends CI_Controller{
        
        public function __construct(){

            parent::__construct();
            // cek session admin yaitu level 2
            if($_SESSION['karyawan']['level']){
                $sessionLevel = $_SESSION['karyawan']['level'];
                if($sessionLevel != 2){
                // jika level bukan 2, redirect ke login
                redirect('/');
                }
            }
            else{
                redirect('/');
            }

        }

        public function index(){

            $this->load->view('user/header');
            $this->load->view('user/admin');
            $this->load->view('user/footer');

        }

        public function getKaryawanJson(){

            // ambil value nama karyawan
            $idKaryawan = $this->input->post('id_karyawan');

            // request data berdasarkan value
            $result = $this->admin_model->getDataKaryawanJson($idKaryawan);
            echo json_encode($result);
        }

        public function absen(){

            $this->form_validation->set_rules(
                array(
                    array(
                        'field' => 'id_karyawan',
                        'label' => 'NIK',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'status',
                        'label' => 'Status',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'tanggaldari',
                        'label' => 'Tanggal Dari',
                        'rules' => 'required'
                    ),
                    array(
                        'field' => 'tanggalsampai',
                        'label' => 'Tanggal Sampai',
                        'rules' => 'required'
                    )
                )
            );

            if(!$this->form_validation->run()){
                $data['absen'] = array();
                $data['tanggal'] = array();

                $this->load->view('user/header');
                $this->load->view('user/adminabsen', $data);
                $this->load->view('user/footer');
            }
            else{
                $id_karyawan = $this->input->post('id_karyawan');
                $status = $this->input->post('status');
                $tanggaldari = $this->input->post('tanggaldari');
                $tanggalsampai = $this->input->post('tanggalsampai');
                $dataambil = array(
                    'id_karyawan' => $id_karyawan,
                    'dari' => $tanggaldari,
                    'status' => $status,
                    'sampai' => $tanggalsampai
                );

                // ambil semua data pembelian
                $dataAbsen = $this->admin_model->getDataAbsenJoin($dataambil);
                $data['absen'] = $dataAbsen;
                $data['tanggal'] = array(
                    'dari' => $tanggaldari,
                    'sampai' => $tanggalsampai
                );
                $data['status'] = $status;

                $this->load->view('user/header');
                $this->load->view('user/adminabsen', $data);
                $this->load->view('user/footer');
            }

        }

    }