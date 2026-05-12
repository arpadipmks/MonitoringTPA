<?php

class Absensi {

    public $tanggal;
    public $status;
    public $id_siswa;
    public $id_guru;

    // Constructor
    public function __construct(
        $tanggal,
        $status,
        $id_siswa,
        $id_guru
    ) {

        $this->tanggal = $tanggal;
        $this->status = $status;
        $this->id_siswa = $id_siswa;
        $this->id_guru = $id_guru;
    }

    // Simpan absensi
    public function simpanAbsensi($koneksi) {

        $query = "INSERT INTO absensi
        (
            tanggal,
            status,
            id_siswa,
            id_guru
        )

        VALUES
        (
            '$this->tanggal',
            '$this->status',
            '$this->id_siswa',
            '$this->id_guru'
        )";

        mysqli_query($koneksi, $query);
    }
}

?>