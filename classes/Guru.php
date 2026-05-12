<?php

require_once 'User.php';

class Guru extends User {

    public $id_guru;
    public $nama;
 
    // Constructor
    public function __construct(
        $id_guru,
        $nama,
        $username,
        $password
    ) {

        parent::__construct($username, $password);

        $this->id_guru = $id_guru;
        $this->nama = $nama;
    }

    // Method input absensi
    public function inputAbsensi() {

    return true;
    }

    // Method input progress
    public function inputProgress() {

        return true;
    }
}

?>