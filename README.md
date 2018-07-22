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
    $connect = mysqli_query(mysqli_connect('localhost', 'root', '', 'db_blog'), "SELECT * FROM artikel");
    $arrayDokumen = [];
    while ($row = mysqli_fetch_assoc($connect)) {
        $arrayDoc = [
            'id_doc' => $row['id_artikel'],
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

## Authors

* **Andri Laksono** - *Programmer* - [Satria Tech](https://satriatech.com)

## License

This project is licensed under the MIT License
