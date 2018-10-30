<?php

    class Karyawan extends CI_Controller{

        public function __construct(){

            parent::__construct();
            // cek session admin yaitu level 1
            if($_SESSION['karyawan']['level']){
                $sessionLevel = $_SESSION['karyawan']['level'];
                if($sessionLevel != 1){
                // jika level bukan 1, redirect ke login
                redirect('/');
                }
            }
            else{
                redirect('/');
            }

        }

        public function index(){

            $this->load->view('header');
            $this->load->view('user/karyawan');
            $this->load->view('footer');

        }

    }