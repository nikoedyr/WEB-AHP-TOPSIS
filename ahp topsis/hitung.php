<style>
    .text-primary{font-weight: bold;}
</style>
<div class="page-header">
    <h1>Perhitungan</h1>
</div>
<?php    
    $c = $db->get_results("SELECT * FROM tb_rel_alternatif WHERE nilai>0");
    if (!$ALTERNATIF|| !$KRITERIA):
        echo "Tampaknya anda belum mengatur alternatif dan kriteria. Silahkan tambahkan minimal 3 alternatif dan 3 kriteria.";
    elseif (!$c):
        echo "Tampaknya anda belum mengatur nilai alternatif. Silahkan atur pada menu <strong>Nilai Bobot</strong> > <strong>Nilai Bobot Alternatif</strong>.";
    else:
?>
<div class="panel panel-primary">
<div class="panel-heading"><strong>Mengukur Konsistensi Kriteria (AHP)</strong></div>
<div class="panel-body">
    <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#c11" aria-expanded="false" aria-controls="c11">
                Matriks Perbandingan Kriteria
            </a>
        </h3>
    </div>
    <div class="panel-body collapse" id="c11">
    <p>Pertama-tama menyusun hirarki dimana diawali dengan tujuan, kriteria dan alternatif-alternatif lokasi pada tingkat paling bawah. 
    Selanjutnya menetapkan perbandingan berpasangan antara kriteria-kriteria dalam bentuk matrik. 
    Nilai diagonal matrik untuk perbandingan suatu elemen dengan elemen itu sendiri diisi dengan bilangan (1) sedangkan isi nilai perbandingan antara (1) sampai dengan (9) kebalikannya, kemudian dijumlahkan perkolom. 
    Data matrik tersebut seperti terlihat pada tabel berikut.</p> 
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
        <?php           
            $matriks = AHP_get_relkriteria();   
            $total = AHP_get_total_kolom($matriks);
            
            echo "<thead><tr><th></th>";     
            foreach($matriks as $key => $value){
                echo "<th class='nw'>$key</th>";        
            }    
            echo "<tr></thead>";    
            foreach($matriks as $key => $value){
                echo "<tr><th class='nw'>$key</th>";
                foreach($value as $k => $v){
                    echo "<td>".round($v,3)."</td>";
                }        
                echo "</tr>";
            }    
            echo "<tfoot><tr><th class='nw'>Total</th>";
            foreach($total as $key => $value){
                echo "<td class='text-primary'>".round($total[$key],3)."</td>";        
            }
            echo "</tr></tfoot>";
        ?>
        </table>
    </div>
    </div>
    
    </div>
    
    <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#c12" aria-expanded="false" aria-controls="c12">
                Matriks Bobot Prioritas Kriteria
            </a>
        </h3>
    </div>
    <div class="panel-body collapse" id="c12">
    <p>Setelah terbentuk matrik perbandingan maka dilihat bobot prioritas untuk perbandingan  kriteria.  
    Dengan  cara  membagi  isi  matriks  perbandingan  dengan jumlah  kolom  yang  bersesuaian,  kemudian  menjumlahkan  perbaris  setelah  itu hasil penjumlahan dibagi dengan banyaknya kriteria sehingga ditemukan bobot prioritas seperti terlihat pada berikut.</p>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
        <?php                
            $normal = AHP_normalize($matriks, $total);                  
            $rata = AHP_get_rata($normal);
        	
            echo "<thead><tr><th></th>";   
            $no=1;
            foreach($normal as $key => $value){
                echo "<th class='nw'>$key</th>";
                $no++;      
            }      
        	echo "<th class='nw'>Bobot Prioritas</th></tr></thead>";  
            $no=1;
            foreach($normal as $key => $value){
                echo "<tr>";
                echo "<th class='nw'>$key</th>";
                foreach($value as $k => $v){
                    echo "<td>".round($v,3)."</td>";
                }				             
                echo "<td class='text-primary'>".round($rata[$key],3)."</td>";
                echo "</tr>";
                $no++;
            }    
            echo "</tr>";	
        ?>
        </table> 
    </div> 
    </div>
    
    </div>
    
    <div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#accordion" href="#c13" aria-expanded="false" aria-controls="c13">
                Matriks Konsistensi Kriteria
            </a>
        </h3>
    </div>
    <div class="panel-body collapse" id="c13">
    <p>Untuk  mengetahui  konsisten  matriks  perbandingan dilakukan  perkalian  seluruh  isi  kolom  matriks  A  perbandingan  dengan  bobot prioritas  kriteria  A,  isi  kolom  B  matriks  perbandingan  dengan  bobot  prioritas kriteria  B  dan  seterusnya.  Kemudian  dijumlahkan  setiap  barisnya  dan  dibagi penjumlahan baris dengan bobot prioritas bersesuaian seperti terlihat pada tabel berikut.</p> 
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover">
        <?php                        
            $cm = AHP_consistency_measure($matriks, $rata);
        	
            echo "<thead><tr><th></th>";   
            $no=1;
            foreach($normal as $key => $value){
                echo "<th class='nw'>$key</th>";
                $no++;      
            }      
        	echo "<th>Bobot</th></tr></thead>";  
            $no=1;
            foreach($normal as $key => $value){
                echo "<tr>";
                echo "<th class='nw'>$key</th>";
                foreach($value as $k => $v){
                    echo "<td>".round($v,3)."</td>";
                }				             
        		echo "<td class='text-primary'>".round($cm[$key],3)."</td>";
                echo "</tr>";
                $no++;
            }    
            echo "</tr>";	
        ?>
        </table> 
    </div>
    <p>Berikut tabel ratio index berdasarkan ordo matriks.</p>    
    
    <table class="table table-bordered">
        <tr>
        <th>Ordo matriks</th>
        <?php
            foreach($nRI as $key => $value){
                if(count($matriks)==$key)
                    echo "<td class='text-primary'>$key</td>";
                else
                    echo "<td>$key</td>";
            }
        ?>
        </tr>
        <tr>
        <th>Ratio index</th>
        <?php
            foreach($nRI as $key => $value){
                if(count($matriks)==$key)
                    echo "<td class='text-primary'>$value</td>";
                else
                    echo "<td>$value</td>";
            }
        ?>
        </tr>
    </table>
    </div>
    <div class="panel-footer">
    <?php
        $CI = ((array_sum($cm)/count($cm))-count($cm))/(count($cm)-1);	
    	$RI = $nRI[count($matriks)];
    	$CR = $CI/$RI;
    	echo "<p>Consistency Index: ".round($CI, 3)."<br />";	
    	echo "Ratio Index: ".round($RI, 3)."<br />";
    	echo "Consistency Ratio: ".round($CR, 3);
    	if($CR>0.10){
    		echo " (Tidak konsisten)<br />";	
    	} else {
    		echo " (Konsisten)<br />";
    	}
    ?>
    </div>
    </div>
</div>
</div>
<div class="panel panel-primary">
<div class="panel-heading"><strong>Perhitungan TOPSIS</strong></div>
<div class="panel-body">
<div class="panel panel-primary">
    <div class="panel-heading"><strong>Hasil Analisa</strong></div>
    <div class="panel-body oxa"> 
        <table class="table table-bordered table-striped table-hover">
        <?php                                            
            echo TOPSIS_hasil_analisa();                    
        ?>
        </table>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><strong>Normalisasi</strong></div>
    <div class="panel-body oxa">
        <table class="table table-bordered table-striped table-hover">
        <?php    
                    
        $normal = TOPSIS_nomalize(TOPSIS_get_hasil_analisa(false));
        
        $r.= "<tr><th></th>";   	
        $no=1;	
        foreach($normal[key($normal)] as $key => $value){
            $r.= "<th>$key</th>";
            $no++;      
        }    
        
        $no=1;	
        foreach($normal as $key => $value){
            $r.= "<tr>";
            $r.= "<th>A".$no."</th>";
            foreach($value as $k => $v){
                $r.= "<td>".round($v,5)."</td>";
            }        
            $r.= "</tr>";
            $no++;    
        }    
        $r.= "</tr>"; 
        echo  $r;
        ?>
        </table>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><strong>Normalisasi Terbobot</strong></div>
    <div class="panel-body oxa">
        <table class="table table-bordered table-striped table-hover">
        <?php    
        $r="";            
        $terbobot = TOPSIS_nomal_terbobot($normal, $rata);
        
        $r.= "<tr><th></th>";   	
        $no=1;	
        foreach($terbobot[key($terbobot)] as $key => $value){
            $r.= "<th>$key</th>";
            $no++;      
        }    
        
        $no=1;	
        foreach($terbobot as $key => $value){
            $r.= "<tr>";
            $r.= "<th>$key</th>";
            foreach($value as $k => $v){
                $r.= "<td>".round($v,5)."</td>";
            }        
            $r.= "</tr>";
            $no++;    
        }    
        $r.= "</tr>"; 
        echo  $r;
        ?>
        </table>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><strong>Matriks Solusi Ideal</strong></div>
    <div class="panel-body oxa">
        <table class="table table-bordered table-striped table-hover">
        <?php    
        $r="";            
        $ideal = TOPSIS_solusi_ideal($terbobot);
        
        $r.= "<tr><th></th>";   	
        $no=1;	
        foreach($ideal[key($ideal)] as $key => $value){
            $r.= "<th>".$key."</th>";
            $no++;      
        }    
        
        $no=1;	
        foreach($ideal as $key => $value){
            $r.= "<tr>";
            $r.= "<th>".$key."</th>";
            foreach($value as $k => $v){
                $r.= "<td>".round($v,5)."</td>";
            }        
            $r.= "</tr>";
            $no++;    
        }    
        $r.= "</tr>"; 
        echo  $r;
        ?>
        </table>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><strong>Jarak Solusi &amp; Nilai Preferensi</strong></div>
    <div class="panel-body oxa">
        <table class="table table-bordered table-striped table-hover">
            <tr>
                <th></th>
                <th>Positif</th>
                <th>Negatif</th>
                <th>Preferensi</th>
            </tr>
        <?php            
        $jarak = TOPSIS_jarak_solusi($terbobot, $ideal);        
        $pref = TOPSIS_preferensi($jarak);
                        
        foreach($normal as $key => $value){
            echo"<tr>";
            echo"<th>$key</th>";
            echo"<td>".round($jarak[$key]['positif'], 5)."</td>";
            echo"<td>".round($jarak[$key]['negatif'], 5)."</td>";
            echo"<td>".round($pref[$key], 5)."</td>";
            echo "</tr>";
            $no++;    
        }                            
        ?>
        </table>        
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading"><strong>Perangkingan</strong></div>
    <div class="panel-body oxa">
        <table class="table table-bordered table-striped table-hover">
        <tr>
            <th></th>
            <th>Total</th>
            <th>Rank</th>
        </tr>
        <?php     
        
        $rank = get_rank($pref);
        
        foreach($normal as $key => $value){
            $db->query("UPDATE tb_alternatif SET total='$pref[$key]', rank='$rank[$key]' WHERE kode_alternatif='$key'");
            echo"<tr>";
            echo"<th>$key - $ALTERNATIF[$key]</th>";
            echo "<td class='text-primary'>".round($pref[$key], 3)."</td>";
            echo "<td class='text-primary'>".$rank[$key]."</td>";
            echo "</tr>";
            $no++;    
        }                            
        ?>
        </table>            
        <div class="form-group">
            <a class="btn btn-default" target="_blank" href="cetak.php?m=hitung"><span class="glyphicon glyphicon-print"></span> Cetak</a>
        </div>    
    </div>
</div>

</div>
</div>
<?php endif?>
