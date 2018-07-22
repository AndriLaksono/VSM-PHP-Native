<?php
/**
 * Kelas Helper untuk VSM
 */
class VSM
{

    /**
     * Memetakan term, Q, d1, d2, ..., df
     *
     * @param  array $query
     * @param  array $dokumen
     * @return array
    */
    public static function get_rank($query, $dokumen)
    {
        $term           = VSM::term($query, $dokumen);
        $dokumen_term   = VSM::dokumen_term($dokumen);
        $df             = VSM::df($term, $query, $dokumen_term);
        $idf            = VSM::idf($query, $dokumen_term, $df);
        $bobot          = VSM::bobot($query, $dokumen_term, $idf);
        $cos_similarity = VSM::cosine_similarity($bobot);

        return $cos_similarity;
    }

    /**
     * Mendapatkan term dengan mensortir kata2 yang berbeda
     *
     * @param  array $query
     * @param  array $dokumen
     * @return array
    */
    public static function term($query, $dokumen)
    {
        // query to string
        $query = implode(" ",  $query);

        // dokumen to array | remove nested array
        $arrayTampung = [];
        foreach ($dokumen as $key => $value) {
            foreach ($value as $key1 => $value1) {
                if ($key1 == 'dokumen') {
                    array_push($arrayTampung, $value1);
                }
            }
        }

        // menggabungkan query ke term
        array_push($arrayTampung, $query);

        // semua value $arrayTampung jadi satu string
        $string_term = implode(" ", $arrayTampung);
        // semua string jadi array | untuk mendapatkan term
        $string_array = explode(" ", $string_term);

        // mendapatkan term
        $word       = str_word_count($string_term, 1); // auto string to array
        $term       = array_count_values($word);

        return $term;
    }

    /**
     * Mendapatkan term dari masing-masing dokumen
     *
     * @param  array $dokumen
     * @return array
    */
    public static function dokumen_term($dokumen)
    {
        $arrayTampung = [];
        foreach ($dokumen as $key => $value) {
            // semua string jadi array | untuk mendapatkan term
            $string_array = explode(" ", $value['dokumen']);
            // mendapatkan term
            $word       = str_word_count($value['dokumen'], 1); // auto string to array
            $term       = array_count_values($word);
            array_push($arrayTampung, ['id_doc' => $value['id_doc'], 'dokumen' => $term]);
        }
        return $arrayTampung;
    }

    /**
     * Mendapatkan nilai df dari masing-masing dokumen & term & query
     *
     * @param  array $term
     * @param  array $query
     * @param  array $dokumen_term
     * @return array
    */
    public static function df($term, $query, $dokumen_term)
    {
        // start from 0 | start dari nol
        $arrayDf = [];
        foreach ($term as $key => $value) {
            $arrayDf[$key] = 0;
        }

        // pengisian df dari $query
        foreach ($term as $key => $value) {
            foreach ($query as $key1 => $value1) {
                if ($key == $value1) {
                    $arrayDf[$key] += 1;
                }
            }
        }

        // pengisian df dari dokumen
        foreach ($term as $key => $value) {
            foreach ($dokumen_term as $key1 => $value1) {
                foreach ($value1['dokumen'] as $key2 => $value2) {
                    if ($key == $key2) {
                        $arrayDf[$key] += 1;
                    }
                }
            }
        }

        return $arrayDf;
    }

    /**
     * Mendapatkan nilai idf dari df
     *
     * @param  array $query
     * @param  array $dokumen_term
     * @param  array $df
     * @return array
    */
    public static function idf($query, $dokumen_term, $df)
    {
        // n = jumlah dokumen + query
        $N_count = count($dokumen_term) + 1;

        $arrayIdf =[];
        foreach ($df as $key => $value) {
            $arrayIdf[$key] = log10( $N_count / $value);
        }

        return $arrayIdf;
    }

    /**
     * Melakukan pembobotan
     *
     * @param  array $query
     * @param  array $dokumen_term
     * @param  array $idf
     * @return array
    */
    public static function bobot($query, $dokumen_term, $idf)
    {
        // pembobotan query
        $bobotQuery =[];
        foreach ($idf as $key => $value) {
            foreach ($query as $key1 => $value1) {
                if ($key == $value1) {
                    $bobotQuery[$key] = (1*$value);
                }
            }
        }

        // pembobotan setiap dokumen
        $bobotDokumen = [];
        foreach ($dokumen_term as $index => $dokumen) {
            $arrayTampung = [];
            foreach ($idf as $key => $value) {
                foreach ($dokumen['dokumen'] as $key1 => $value1) {
                    if ($key == $key1) {
                        $arrayTampung += [$key => ($value*$value1),];
                    }
                }
            }
            array_push($bobotDokumen, array('id_doc' => $dokumen['id_doc'], "dokumen" => $arrayTampung));
        }

        // Array Bobot
        $arrayBobot = ["query" => $bobotQuery, "dokumen" => $bobotDokumen];

        return $arrayBobot;
    }

    /**
     * Melakukan perangking-an dengan cosine similarity
     *
     * @param  array $bobot
     * @return array
    */
    public static function cosine_similarity($bobot)
    {
        // mendapatkan jumlah dan akar dari query @float
        $queryCosJumlah = 0;
        foreach ($bobot['query'] as $key => $value) {
            $queryCosJumlah += $value;
        }
        $queryCosAkar = sqrt($queryCosJumlah);

        // mendapatkan jumlah dan akar dari setiap dokumen @array
        $dokumenCosJumlahAkar = [];
        foreach ($bobot['dokumen'] as $index => $dokumen) { // dokumen 1, 2, 3 ... n
            $arrayDoc[$index] = ["id_doc" => $dokumen['id_doc']];
            $bobotDoc[$index] = 0;
            foreach ($bobot['query'] as $key => $value) {
                foreach ($dokumen['dokumen'] as $key1 => $value1) { // isi dr dokumen 1, 2, ..n
                    if ($key == $key1) {
                        $bobotDoc[$index] += ($value1 + $value1);
                    }
                }
            }
            $arrayDoc[$index] += ["jumlah_bobot" => $bobotDoc[$index], "akar_bobot" => sqrt($bobotDoc[$index])];
            array_push($dokumenCosJumlahAkar,  $arrayDoc[$index]);
        }

        // mendapatkan vektor
        $dokumenVektor = [];
        foreach ($bobot['dokumen'] as $index => $dokumen) { // dokumen 1, 2, 3 ... n
            $arrayDoc[$index] = ["id_doc" => $dokumen['id_doc']];
            $vektorDoc[$index] = 0;
            foreach ($bobot['query'] as $key => $value) {
                foreach ($dokumen['dokumen'] as $key1 => $value1) { // isi dr dokumen 1, 2, ..n
                    if ($key == $key1) {
                        $vektorDoc[$index] += ($value * $value1);
                    }
                }
            }
            $arrayDoc[$index] += ["jumlah_vektor" => $vektorDoc[$index] ];
            array_push($dokumenVektor,  $arrayDoc[$index]);
        }

        // mendapatkan besar vektor
        $dokumenBesarVektor = [];
        foreach ($dokumenCosJumlahAkar as $index => $dokumen) {
            $arrayDoc[$index] = ["id_doc" => $dokumen['id_doc']];
            $besarVektorDoc[$index] = $dokumen['akar_bobot'];

            $arrayDoc[$index] += ["jumlah_besar_vektor" => ($besarVektorDoc[$index] * $queryCosAkar) ];
            array_push($dokumenBesarVektor,  $arrayDoc[$index]);
        }
        // dd($dokumenVektor, $dokumenBesarVektor);

        // gabungkan array vektor dan besar vektor
        $dokumenCosine = [];
        foreach ($dokumenVektor as $index => $dokumen) {
            $arrayDoc[$index] = ["id_doc" => $dokumen['id_doc'], "jumlah_vektor" => $dokumen['jumlah_vektor'] ];
            $vectorCosine[$index] = 0;

            foreach ($dokumenBesarVektor as $index1 => $dokumen1) {
                if ($dokumen1['id_doc'] == $dokumen['id_doc']) {
                    $vectorCosine[$index] = $dokumen1["jumlah_besar_vektor"];

                    $arrayDoc[$index] += ["jumlah_besar_vektor" => $vectorCosine[$index] ];
                    array_push($dokumenCosine,  $arrayDoc[$index]);
                }
            }
        }

        // membuat ranking
        $dokumenRanking = [];
        foreach ($dokumenCosine as $index => $dokumen) {
            $jumlah = 0;
            if ($dokumen["jumlah_vektor"] != 0 && $dokumen["jumlah_besar_vektor"] != 0) {
                $jumlah = $dokumen["jumlah_vektor"] / $dokumen["jumlah_besar_vektor"];
            }
            $arrayDoc[$index] = ["id_doc" => $dokumen['id_doc'], "ranking" => $jumlah];
            array_push($dokumenRanking, $arrayDoc[$index]);
        }

        return $dokumenRanking;
    }


} // end class