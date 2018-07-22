<?php

include 'config.php';
define("HOSTNAME_DB", $database['hostname'], TRUE);
define("USERNAME_DB", $database['username'], TRUE);
define("PASSWORD_DB", $database['password'], TRUE);
define("DATABASE_DB", $database['database'], TRUE);

/**
 * Kelas Module untuk Preprocess
 */
class Preprocessing
{
    /**
     * Memanggil semua fungsi
     *
     * @param  string  $query
     * @return array
    */
    public static function preprocess($query)
    {        
        $query_case     = Preprocessing::case_folding($query);
        $query_token    = Preprocessing::tokenizing($query_case);
        $query_filter   = Preprocessing::filtering($query_token);
        $query_dasar    = Preprocessing::stemming($query_filter);
        return $query_dasar;
    }

    /**
     * Mengubah teks menjadi lowercase
     *
     * @param  string  $query
     * @return string
    */
    public static function case_folding($query)
    {
        return strtolower($query);
    }

    /**
     * Membuang tanda2 baca, ex: .,:?- dsb
     *
     * @param  string  $query
     * @return array
    */
    public static function tokenizing($query)
    {
        // Removes special chars.
        $string = preg_replace('/[^A-Za-z0-9\-]/', ' ', $query);
        // Replaces multiple spasi with single spasi.
        $string = preg_replace('!\s+!', ' ', $string);
        // String to array
        $string_array = explode(" ", $string);

        return $string_array;
    }

    /**
     * Membuang kata yang tidak perlu
     *
     * @param  array  $query
     * @return array
    */
    public static function filtering($query)
    {
        $stopword = array(' about', ' adalah', ' after', ' agak', ' agar', ' akan', ' akibat', ' akibatnya', ' all', ' also', ' amatlah', ' an', ' and', ' another', ' antara', ' any', ' apa', ' apa', ' apabila', ' apakah', ' apalagi', ' are', ' as', ' at', ' atau', ' b', ' bagai', ' bagai', ' bagaikan', ' bagaimana', ' bagi', ' bahkan', ' bahwa', ' bahwa', ' be', ' because', ' been', ' before', ' beginian', ' begitu', ' being', ' belum', ' berdatangan', ' berlainan', ' bersama', ' betulkah', ' between', ' biar', ' biarpun', ' bila', ' bilamana', ' bolehkah', ' both', ' but', ' by', ' c', ' came', ' can', ' caranya', ' come', ' contoh', ' could', ' d', ' dalam', ' dan', ' semenjak', ' sementara', ' semisal', ' sepanjang', ' sepantasnyalah', ' seperti', ' sering', ' serta', ' sesudah', ' setelah', ' should', ' since', ' so', ' some', ' still', ' such', ' sudah', ' sudah', ' supaya', ' t', ' take', ' tanpa', ' tapi', ' telah', ' telah', ' tentang', ' tentang', ' terhadap', ' terlalu', ' tersebut', ' tersebutlah', ' terus', ' tetapi', ' than', ' that', ' the', ' their', ' them', ' then', ' there', ' these', ' they', ' this', ' those', ' through', ' to', ' too', ' u', ' umpama', ' under', ' untuk', ' up', ' use', ' v', ' very', ' w', ' walau', ' walaupun', ' want', ' was', ' way', ' we', ' well', ' were', ' what', ' when', ' where', ' which', ' while', ' who', ' will', ' with', ' would', ' x', ' y', ' yaitu', ' yakni', ' you', ' your', ' z', 'ada', 'adalah', 'adanya', 'adapun', 'agaknya', 'agar', 'akan', 'akankah', 'akhir', 'akhiri', 'akhirnya', 'aku', 'akulah', 'amat', 'anda', 'andalah', 'antar', 'antara', 'antaranya', 'apaan', 'apabila', 'apakah', 'apalagi', 'apatah', 'artinya', 'asal', 'asalkan', 'atas', 'atau', 'ataukah', 'ataupun', 'awal', 'awalnya', 'bagaikan', 'bagaimana', 'bagaimanakah', 'bagaimanapun', 'bagi', 'bagian', 'bahkan', 'bahwasanya', 'baik', 'bakal', 'bakalan', 'balik', 'banyak', 'bapak', 'baru', 'bawah', 'beberapa', 'begini', 'beginikah', 'beginilah', 'begitu', 'begitukah', 'begitulah', 'begitupun', 'bekerja', 'belakang', 'belakangan', 'belum', 'belumlah', 'benar', 'benarkah', 'benarlah', 'berada', 'berakhir', 'berakhirlah', 'berakhirnya', 'berapa', 'berapakah', 'berapalah', 'berapapun', 'berarti', 'berawal', 'berbagai', 'beri', 'berikan', 'berikut', 'berikutnya', 'berjumlah', 'berkali-kali', 'berkata', 'berkehendak', 'berkeinginan', 'berkenaan', 'berlalu', 'berlangsung', 'berlebihan', 'bermacam', 'bermacam-macam', 'bermaksud', 'bermula', 'bersama', 'bersama-sama', 'bersiap', 'bersiap-siap', 'bertanya', 'bertanya-tanya', 'berturut', 'berturut-', 'bertutur', 'berujar', 'berupa', 'besar', 'betul', 'biasa', 'biasanya', 'bila', 'bilakah', 'bisa', 'bisakah', 'boleh', 'bolehlah', 'buat', 'bukan', 'bukankah', 'bukanlah', 'bukannya', 'bulan', 'bung', 'cara', 'cukup', 'cukupkah', 'cukuplah', 'cuma', 'dahulu', 'dalam', 'dan', 'dapat', 'dari', 'datang', 'dekat', 'demi', 'demikian', 'demikianlah', 'dengan', 'depan', 'di', 'dia', 'diakhiri', 'diakhirinya', 'dialah', 'diantara', 'diantaranya', 'diberi', 'diberikan', 'diberikannya', 'dibuat', 'dibuatnya', 'didapat', 'didatangkan', 'digunakan', 'diibaratkan', 'diibaratkannya', 'diingat', 'diingatkan', 'diinginkan', 'dijawab', 'dijelaskan', 'dijelaskannya', 'dikatakan', 'dikatakannya', 'dikerjakan', 'diketahui', 'diketahuinya', 'dikira', 'dilakukan', 'dilalui', 'dilihat', 'dimaksud', 'dimaksudkan', 'dimaksudkannya', 'dimaksudnya', 'diminta', 'dimintai', 'dimisalkan', 'dimulai', 'dimulailah', 'dimulainya', 'dini', 'dipastikan', 'diperbuat', 'diperbuatnya', 'dipergunakan', 'diperkirakan', 'diperlihatkan', 'diperlukannya', 'dipersoalkan', 'dipertanyakan', 'dipunyai', 'diri', 'disampaikan', 'disebut', 'disebutkan', 'disebutkannya', 'disini', 'disinilah', 'ditambahkan', 'ditandaskan', 'ditanya', 'ditanyai', 'ditanyakan', 'ditegaskan', 'ditujukan', 'ditunjuk', 'ditunjuki', 'ditunjukkan', 'ditunjukkannya', 'ditunjuknya', 'dituturkan', 'dituturkannya', 'diucapkan', 'diucapkannya', 'diungkapkan', 'dong', 'dua', 'dulu', 'empat', 'enggak', 'enggaknya', 'entah', 'entahlah', 'guna', 'gunakan', 'hal', 'hampir', 'hanya', 'hari', 'harus', 'haruslah', 'harusnya', 'hendak', 'hendaklah', 'hingga', 'ia', 'ialah', 'ibarat', 'ibaratkan', 'ibaratnya', 'ibu', 'if', 'ikut', 'ingat', 'ingat-ingat', 'ingin', 'inginkah', 'inginkan', 'ini', 'inikah', 'inilah', 'itu', 'itukah', 'itulah', 'jadi', 'jadilah', 'jadinya', 'jangan', 'jangankan', 'janganlah', 'jauh', 'jawab', 'jawaban', 'jawabnya', 'jelas', 'jelaskan', 'jelaslah', 'jelasnya', 'jika', 'juga', 'jumlah', 'jumlahnya', 'justru', 'kala', 'kalau', 'kalaulah', 'kalaupun', 'kalian', 'kami', 'kamilah', 'kamu', 'kamulah', 'kan', 'kapan', 'kapankah', 'kapanpun', 'kasus', 'kata', 'katakan', 'katakanlah', 'katanya', 'ke', 'keadaan', 'kebetulan', 'kecil', 'kedua', 'keduanya', 'keinginan', 'kelamaan', 'kelihatan', 'kelihatannya', 'kelima', 'keluar', 'kembali', 'kemudian', 'kemungkinan', 'kemungkinannya', 'kepada', 'kepadanya', 'kesampaian', 'keseluruhan', 'keseluruhannya', 'keterlaluan', 'ketika', 'khususnya', 'kini', 'kinilah', 'kira', 'kira-kira', 'kiranya', 'kita', 'kitalah', 'kurang', 'lagi', 'lagian', 'lah', 'lain', 'lainnya', 'lalu', 'lama', 'lamanya', 'lanjut', 'lanjutnya', 'lebih', 'lewat', 'lima', 'luar', 'macam', 'maka', 'makanya', 'makin', 'malah', 'malahan', 'mampu', 'mana', 'manakala', 'manalagi', 'masa', 'masalah', 'masalahnya', 'masih', 'masihkah', 'masing', 'masing-masing', 'mau', 'maupun', 'melainkan', 'melakukan', 'melalui', 'melihat', 'melihatnya', 'memang', 'memastikan', 'memberi', 'memberikan', 'membuat', 'memerlukan', 'memihak', 'meminta', 'memintakan', 'memisalkan', 'memperbuat', 'mempergunakan', 'memperkirakan', 'memperlihatkan', 'mempersiapkan', 'mempersoalkan', 'mempertanyakan', 'mempunyai', 'memulai', 'memungkinkan', 'menaiki', 'menambahkan', 'menandaskan', 'menanti', 'menantikan', 'menanti-nanti', 'menanya', 'menanyai', 'menanyakan', 'mendapat', 'mendapatkan', 'mendatang', 'mendatangi', 'mendatangkan', 'menegaskan', 'mengakhiri', 'mengapa', 'mengatakan', 'mengatakannya', 'mengenai', 'mengerjakan', 'mengetahui', 'menggunakan', 'menghendaki', 'mengibaratkan', 'mengibaratkannya', 'mengingat', 'mengingatkan', 'menginginkan', 'mengira', 'mengucapkan', 'mengucapkannya', 'mengungkapkan', 'menjadi', 'menjawab', 'menjelaskan', 'menuju', 'menunjuk', 'menunjuki', 'menunjukkan', 'menunjuknya', 'menurut', 'menuturkan', 'menyampaikan', 'menyangkut', 'menyatakan', 'menyebutkan', 'menyeluruh', 'menyiapkan', 'merasa', 'mereka', 'merekalah', 'merupakan', 'meski', 'meskipun', 'meyakini', 'meyakinkan', 'minta', 'mirip', 'misal', 'misalkan', 'misalnya', 'mula', 'mulai', 'mulailah', 'mulanya', 'mungkinkah', 'nah', 'naik', 'namun', 'nanti', 'nantinya', 'nyaris', 'nyatanya', 'oleh', 'olehnya', 'pada', 'padahal', 'padanya', 'pak', 'paling', 'panjang', 'pantas', 'para', 'pasti', 'penting', 'pentingnya', 'per', 'percuma', 'perlu', 'perlukah', 'perlunya', 'pernah', 'persoalan', 'pertama', 'pertama-tama', 'pertanyakan', 'pihak', 'pihaknya', 'pukul', 'pula', 'pun', 'punya', 'rasa', 'rasanya', 'rata', 'rupanya', 'saat', 'saatnya', 'saja', 'sajalah', 'saling', 'sama', 'sama-sama', 'sambil', 'sampai', 'sampaikan', 'sampai-sampai', 'sana', 'sangatlah', 'satu', 'saya', 'sayalah', 'se', 'sebab', 'sebabnya', 'sebagaimana', 'sebagainya', 'sebagian', 'sebaik', 'sebaik-baiknya', 'sebaiknya', 'sebaliknya', 'sebanyak', 'sebegini', 'sebelum', 'sebelumnya', 'sebenarnya', 'seberapa', 'sebesar', 'sebetulnya', 'sebisanya', 'sebuah', 'sebut', 'sebutlah', 'sebutnya', 'secara', 'secukupnya', 'sedang', 'sedangkan', 'sedemikian', 'sedikit', 'sedikitnya', 'seenaknya', 'segala', 'segalanya', 'segera', 'seharusnya', 'sehingga', 'seingat', 'sejak', 'sejauh', 'sejenak', 'sejumlah', 'sekadar', 'sekadarnya', 'sekali', 'sekalian', 'sekaligus', 'sekali-kali', 'sekarang', 'sekarang', 'sekecil', 'seketika', 'sekiranya', 'sekitar', 'sekitarnya', 'sekurang-kurangnya', 'sela', 'selain', 'selaku', 'selalu', 'selama', 'selama-lamanya', 'selamanya', 'selanjutnya', 'seluruh', 'semacam', 'semakin', 'semampu', 'semampunya', 'semasa', 'semasih', 'semata', 'semata-mata', 'semaunya', 'sementara', 'semisalnya', 'tadinya', 'tahu', 'tahun', 'tak', 'tambah', 'tambahnya', 'tampak', 'tampaknya', 'tandas', 'tandasnya', 'tanpa', 'tanya', 'tanyakan', 'tanyanya', 'tapi', 'tegas', 'tegasnya', 'tempat', 'tengah', 'tentu', 'tentulah', 'tentunya', 'tepat', 'terakhir', 'terasa', 'terbanyak', 'terdahulu', 'terdapat', 'terdiri', 'terhadap', 'terhadapnya', 'teringat', 'teringat-ingat', 'terjadi', 'terjadilah', 'terjadinya', 'terkira', 'terlebih', 'terlihat', 'termasuk', 'ternyata', 'tersampaikan', 'tersebut', 'tertentu', 'tertuju', 'terus', 'terutama', 'tetap', 'tetapi', 'tiap', 'tiba', 'tiba-tiba', 'tidak', 'tidakkah', 'tidaklah', 'tiga', 'tinggi', 'toh', 'tunjuk', 'turut', 'tutur', 'tuturnya', 'ucap', 'ucapnya', 'ujar', 'ujarnya', 'umum', 'umumnya', 'ungkap', 'ungkapnya', 'untuk', 'usah', 'usai', 'waduh', 'wah', 'wahai', 'waktu', 'waktunya', 'walau', 'walaupun', 'wong', 'yaitu', 'yakin', 'yakni', 'yang');

        $string = preg_replace('/\b('.implode('|',$stopword).')\b/','',implode(" ", $query));

        // Replaces multiple spasi with single spasi.
        $string = preg_replace('!\s+!', ' ', $string);
        // String to array
        $string_array = explode(" ", $string);
        $query_array  = [];
        foreach ($string_array as $key => $value) {
            if ($value != NULL || $value != "") {
                array_push($query_array, $value);
            }
        }
        return $query_array;
    }


    /**
     * Mengganti dengan kata dasar disetiap kolom array
     *
     * @param  array  $query
     * @return array
    */
    public static function stemming($query)
    {
        $kelas = new Preprocessing;
        // $cari  = $kelas->cari("lihat");
        $query_array = [];
        foreach ($query as $key => $value) {
            array_push($query_array, $kelas->katadasar($value));
        }
        return $query_array;
    }


    // ========== fungsi pendukung Preprocessing ========= //
    function cari($kata){
        $host = constant("hostname_db");
        $user = constant("username_db");
        $pass = constant("password_db");
        $db   = constant("database_db");
        $hasil = mysqli_query(mysqli_connect($host, $user, $pass, $db), "SELECT * FROM tb_katadasar WHERE katadasar = '".$kata."'");
        $row = mysqli_fetch_assoc($hasil);
        return $row['katadasar'] ? $row['katadasar'] : NULL;
    }

    //langkah 1 - hapus partikel
    function hapuspartikel($kata){
        $kelas = new Preprocessing;
        if($kelas->cari($kata)==NULL){
            if((substr($kata, -3) == 'kah' ) || ( substr($kata, -3) == 'lah' ) || ( substr($kata, -3) == 'pun' )) {
                $kata = substr($kata, 0, -3);
            }
        }
        return $kata;
    }

    //langkah 2 - hapus possesive pronoun
    function hapuspp($kata){
        $kelas = new Preprocessing;
        if($kelas->cari($kata)==NULL){
            if(strlen($kata) > 4){
                if((substr($kata, -2)== 'ku')||(substr($kata, -2)== 'mu')){
                    $kata = substr($kata, 0, -2);
                }else if((substr($kata, -3)== 'nya')){
                    $kata = substr($kata,0, -3);
                }
            }
        }
        return $kata;
    }

    //langkah 3 hapus first order prefiks (awalan pertama)
    function hapusawalan1($kata){
        $kelas = new Preprocessing;
        if($kelas->cari($kata)==NULL){
            if(substr($kata,0,4)=="meng"){
                if(substr($kata,4,1)=="e"||substr($kata,4,1)=="u"){
                    $kata = "k".substr($kata,4);
                }else{
                    $kata = substr($kata,4);
                }
            }else if(substr($kata,0,4)=="meny"){
                $kata = "s".substr($kata,4);
            }else if(substr($kata,0,3)=="men"){
                $kata = substr($kata,3);
            }else if(substr($kata,0,3)=="mem"){
                if(substr($kata,3,1)=="a" || substr($kata,3,1)=="i" || substr($kata,3,1)=="e" || substr($kata,3,1)=="u" || substr($kata,3,1)=="o"){
                    $kata = "p".substr($kata,3);
                }else{
                    $kata = substr($kata,3);
                }
            }else if(substr($kata,0,2)=="me"){
                $kata = substr($kata,2);
            }else if(substr($kata,0,4)=="peng"){
                if(substr($kata,4,1)=="e" || substr($kata,4,1)=="a"){
                    $kata = "k".substr($kata,4);
                }else{
                    $kata = substr($kata,4);
                }
            }else if(substr($kata,0,4)=="peny"){
                $kata = "s".substr($kata,4);
            }else if(substr($kata,0,3)=="pen"){
                if(substr($kata,3,1)=="a" || substr($kata,3,1)=="i" || substr($kata,3,1)=="e" || substr($kata,3,1)=="u" || substr($kata,3,1)=="o"){
                    $kata = "t".substr($kata,3);
                }else{
                    $kata = substr($kata,3);
                }
            }else if(substr($kata,0,3)=="pem"){
                if(substr($kata,3,1)=="a" || substr($kata,3,1)=="i" || substr($kata,3,1)=="e" || substr($kata,3,1)=="u" || substr($kata,3,1)=="o"){
                    $kata = "p".substr($kata,3);
                }else{
                    $kata = substr($kata,3);
                }
            }else if(substr($kata,0,2)=="di"){
                $kata = substr($kata,2);
            }else if(substr($kata,0,3)=="ter"){
                $kata = substr($kata,3);
            }else if(substr($kata,0,2)=="ke"){
                $kata = substr($kata,2);
            }
        }
        return $kata;
    }

    //langkah 4 hapus second order prefiks (awalan kedua)
    function hapusawalan2($kata){
        $kelas = new Preprocessing;
        if($kelas->cari($kata)==NULL){
            if(substr($kata,0,3)=="ber"){
                $kata = substr($kata,3);
            }else if(substr($kata,0,3)=="bel"){
                $kata = substr($kata,3);
            }else if(substr($kata,0,2)=="be"){
                $kata = substr($kata,2);
            }else
            if(substr($kata,0,3)=="per" && strlen($kata) > 5){
                $kata = substr($kata,3);
            }else if(substr($kata,0,2)=="pe"  && strlen($kata) > 5){
                $kata = substr($kata,2);
            }else if(substr($kata,0,3)=="pel"  && strlen($kata) > 5){
                $kata = substr($kata,3);
            }else if(substr($kata,0,2)=="se"  && strlen($kata) > 5){
                $kata = substr($kata,2);
            }
        }
        return $kata;
    }

    ////langkah 5 hapus suffiks
    function hapusakhiran($kata){
        $kelas = new Preprocessing;
        if($kelas->cari($kata)==NULL){
            if (substr($kata, -3)== "kan" ){
                $kata = substr($kata, 0, -3);
            }
            else if(substr($kata, -1) == "i"  && $kata != 'beri'){
                $kata = substr($kata, 0, -1);
            }else if(substr($kata, -2)== "an"){
                $kata = substr($kata, 0, -2);
            }
        }
        return $kata;
    }

    function katadasar($kata)
    {
        $kelas = new Preprocessing;
        return $kelas->hapusakhiran( $kelas->hapusawalan2($kelas->hapusawalan1($kelas->hapuspp($kelas->hapuspartikel($kata)))) );
    }



} // end class