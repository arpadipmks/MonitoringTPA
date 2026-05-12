<?php

class ProgressBelajar {

    public $id_progress;
    public $tanggal;
    public $materi;
    public $progress;

    private $skor;
    private $grade;

    public $catatan;
    public $id_siswa;
    public $id_guru;

    // Constructor
    public function __construct(
        $id_progress,
        $tanggal,
        $materi,
        $progress,
        $catatan,
        $id_siswa,
        $id_guru
    ) {

        $this->id_progress = $id_progress;
        $this->tanggal = $tanggal;
        $this->materi = $materi;
        $this->progress = $progress;
        $this->catatan = $catatan;
        $this->id_siswa = $id_siswa;
        $this->id_guru = $id_guru;
    }

    // Setter skor
    public function setSkor($skor) {

        if ($skor < 0 || $skor > 100) {

            echo "Skor tidak valid";

        } else {

            $this->skor = $skor;

            $this->hitungGrade();
        }
    }

    // Getter skor
    public function getSkor() {

        return $this->skor;
    }

    // Getter grade
    public function getGrade() {

        return $this->grade;
    }

    // Hitung grade otomatis
    public function hitungGrade() {

        if ($this->skor >= 96) {

            $this->grade = "A+";

        } elseif ($this->skor >= 91) {

            $this->grade = "A";

        } elseif ($this->skor >= 86) {

            $this->grade = "B+";

        } elseif ($this->skor >= 80) {

            $this->grade = "B";

        } elseif ($this->skor >= 70) {

            $this->grade = "C+";

        } elseif ($this->skor >= 60) {

            $this->grade = "C";

        } else {

            $this->grade = "D";
        }
    }

    // Simpan ke database
    public function simpanProgress($koneksi) {

        $query = "INSERT INTO progress_belajar
        (
            tanggal,
            materi,
            progress,
            skor,
            grade,
            catatan,
            id_siswa,
            id_guru
        )

        VALUES
        (
            '$this->tanggal',
            '$this->materi',
            '$this->progress',
            '$this->skor',
            '$this->grade',
            '$this->catatan',
            '$this->id_siswa',
            '$this->id_guru'
        )";

        mysqli_query($koneksi, $query);       
    }
}

?>