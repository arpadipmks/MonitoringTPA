<?php

class Siswa {

    public $id_siswa;
    public $nama;

    // Constructor
    public function __construct($id_siswa, $nama) {

        $this->id_siswa = $id_siswa;
        $this->nama = $nama;
    }
}

?>