# VSM PHP Native (Cosine Measurement)
Module VSM untuk membantu pencarian pada PHP menggunakan Information retrieval berbahasa indonesia.

## Setting Up
Untuk menggunakan model ini anda harus melakukan beberapa langkah setup berikut.
* Import table [tb_katadasar.sql](https://github.com/AndriLaksono/VSM-PHP-Native/blob/master/tb_katadasar.sql) ke database anda.
* Letakan **VSMModule** dibawah root project folder anda.
```
-projectanda
---VSMModule
-------config.php
-------VSM.php
-------Preprocessing.php
```

### Penggunaan
Setelah melakukan setup anda dapat menggunakan pada file proses php anda.
Berikut contoh penggunaannya:

**Penggunaan di proses php**
```
<?php
include_once __DIR__."/VSMModule/Preprocessing.php";
include_once __DIR__."/VSMModule/VSM.php";

function pencarian()
{
    // == STEP 1 inisialisasi
    $preprocess = new Preprocessing();
    $vsm = new VSM();

    // == STEP 2 mendapatkan kata dasar
    $query = $preprocess::preprocess($_POST['cari']);

    // == STEP 3 medapatkan dokumen ke array
    $connect = mysqli_query(mysqli_connect('localhost', 'root', '', 'tbi_kriminalitas'), "SELECT * FROM kriminal");
    $arrayDokumen = [];
    while ($row = mysqli_fetch_assoc($connect)) {
        $arrayDoc = [
            'id_doc' => $row['id_kriminal'],
            'dokumen' => implode(" ", $preprocess::preprocess($row['deskripsi']))
        ];
        array_push($arrayDokumen, $arrayDoc);
    }
    
    // STEP 4 == mendapatkan ranking dengan VSM
    $rank = $vsm::get_rank($query, $arrayDokumen);
    var_dump($rank);
    die();

}

// jalankan fungsi
pencarian();
```

Contoh Model CI kami
```
<?php


defined('BASEPATH') OR exit('No direct script access allowed');

class BlogModel extends CI_Model {

    
    public function __construct()
    {
        parent::__construct();
    }

    public function get_data()
    {
        $data = $this->db->get('blog')->result();
        return $data;
    }
    

}
```

## Authors

* **Andri Laksono** - *Programmer* - [Satria Tech](https://satriatech.com)

## License

This project is licensed under the MIT License
