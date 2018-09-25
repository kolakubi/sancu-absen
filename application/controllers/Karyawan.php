<?php

    class Karyawan extends CI_Controller{

        public function __construct(){

            parent::__construct();

        }

        public function index(){

            $this->load->view('header');
            $this->load->view('user/karyawan');
            $this->load->view('footer');

        }

    }