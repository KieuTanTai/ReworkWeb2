<?php
    require_once __DIR__ . '/../../model/User.php';
    require_once __DIR__ . '/../../config/database.php';

    class UserController{
        private $user;
        private $conn;

        public function __construct(){
            $this->conn = $GLOBALS['conn'];
            $this->user = new User($this->conn);
        }

        public function index(){
            $result = $this->user->getAll();

            $users = [];

            if($result && $result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $users[] = $row;
                }
            }

            return $users;
        }

        public function create($data){
            $this->user->tenkhachhang = $data['tenkhachhang'];
            $this->user->diachi = $data['diachi'];
            $this->user->sdt = $data['sdt'];
            $this->user->email = $data['email'];
            $this->user->trangthai = $data['trangthai'];
            $this->user->ngaythamgia = $data['ngaythamgia'];

            return $this->user->create();
        }

        public function update($id, $data){
            $this->user->makhachhang = $id;
            $this->user->tenkhachhang = $data['tenkhachhang'];
            $this->user->diachi = $data['diachi'];
            $this->user->sdt = $data['sdt'];
            $this->user->email = $data['email'];
            $this->user->trangthai = $data['trangthai'];
            $this->user->ngaythamgia = $data['ngaythamgia'];

            return $this->user->update();
        }
    }
?>