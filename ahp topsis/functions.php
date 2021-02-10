<?php
error_reporting(~E_NOTICE);
session_start();

include'config.php';
include'includes/db.php';
$db = new DB($config['server'], $config['username'], $config['password'], $config['database_name']);
include'includes/general.php';    
include'includes/paging.php';
    
$mod = $_GET['m'];
$act = $_GET['act'];  

$nRI = array (
	1=>0,
	2=>0,
	3=>0.58,
	4=>0.9,
	5=>1.12,
	6=>1.24,
	7=>1.32,
	8=>1.41,
	9=>1.46,
	10=>1.49,
    11=>1.51,
    12=>1.48,
    13=>1.56,
    14=>1.57,
    15=>1.59
);

$rows = $db->get_results("SELECT kode_alternatif, nama_alternatif FROM tb_alternatif ORDER BY kode_alternatif");
foreach($rows as $row){
    $ALTERNATIF[$row->kode_alternatif] = $row->nama_alternatif;
}

$rows = $db->get_results("SELECT kode_kriteria, nama_kriteria, atribut FROM tb_kriteria ORDER BY kode_kriteria");
foreach($rows as $row){
    $KRITERIA[$row->kode_kriteria] = array(
        'nama_kriteria'=>$row->nama_kriteria,
        'atribut'=>$row->atribut,
        'bobot'=>$row->bobot
    );
}

function AHP_get_relkriteria(){
    global $db;
    $data = array();
    $rows = $db->get_results("SELECT k.nama_kriteria, rk.ID1, rk.ID2, nilai 
        FROM tb_rel_kriteria rk INNER JOIN tb_kriteria k ON k.kode_kriteria=rk.ID1 
        ORDER BY ID1, ID2");
    foreach($rows as $row){    
        $data[$row->ID1][$row->ID2] = $row->nilai;
    }
    return $data;
}   

function AHP_get_relalternatif($kriteria=''){
    global $db;
    $rows = $db->get_results("SELECT * FROM tb_rel_alternatif WHERE kode_kriteria='$kriteria' ORDER BY kode1, kode2");
    $matriks = array();
    foreach($rows as $row){
        $matriks[$row->kode1][$row->kode2] = $row->nilai;
    }
    return $matriks;
}

function get_kriteria_option($selected = 0){
    global $KRITERIA;  
    foreach($KRITERIA as $key => $value){
        if($key==$selected)
            $a.="<option value='$key' selected>$value[nama_kriteria]</option>";
        else
            $a.="<option value='$key'>$value[nama_kriteria]</option>";
    }
    return $a;
}

function get_atribut_option($selected = ''){
    $atribut = array('benefit'=>'Benefit', 'cost'=>'Cost');   
    foreach($atribut as $key => $value){
        if($selected==$key)
            $a.="<option value='$key' selected>$value</option>";
        else
            $a.= "<option value='$key'>$value</option>";
    }
    return $a;
}

function AHP_get_alternatif_option($selected = ''){
    global $db;
    $rows = $db->get_results("SELECT kode_alternatif, nama_alternatif FROM tb_alternatif ORDER BY kode_alternatif");
    foreach($rows as $row){
        if($row->kode_alternatif==$selected)
            $a.="<option value='$row->kode_alternatif' selected>$row->kode_alternatif - $row->nama_alternatif</option>";
        else
            $a.="<option value='$row->kode_alternatif'>$row->kode_alternatif - $row->nama_alternatif</option>";
    }
    return $a;
}

function AHP_get_nilai_option($selected = ''){
    $nilai = array(
        '1' => 'Sama penting dengan',
        '2' => 'Mendekati sedikit lebih penting dari',
        '3' => 'Sedikit lebih penting dari',
        '4' => 'Mendekati lebih penting dari',
        '5' => 'Lebih penting dari',
        '6' => 'Mendekati sangat penting dari',
        '7' => 'Sangat penting dari',
        '8' => 'Mendekati mutlak dari',
        '9' => 'Mutlak sangat penting dari',
    );
    foreach($nilai as $key => $value){
        if($selected==$key)
            $a.="<option value='$key' selected>$key - $value</option>";
        else
            $a.= "<option value='$key'>$key - $value</option>";
    }
    return $a;
}

function AHP_get_total_kolom($matriks = array()){
    $total = array();        
    foreach($matriks as $key => $value){
        foreach($value as $k => $v){
            $total[$k]+=$v;
        }
    }  
    return $total;
} 

function AHP_normalize($matriks = array(), $total = array()){
          
    foreach($matriks as $key => $value){
        foreach($value as $k => $v){
            $matriks[$key][$k] = $matriks[$key][$k]/$total[$k];
        }
    }     
    return $matriks;       
}

function AHP_get_rata($normal){
    $rata = array();
    foreach($normal as $key => $value){
        $rata[$key] = array_sum($value)/count($value); 
    } 
    return $rata;   
}

function AHP_mmult($matriks = array(), $rata = array()){
	$data = array();
    
    $rata = array_values($rata);
    
	foreach($matriks as $key => $value){
        $no=0;
		foreach($value as $k => $v){
			$data[$key]+=$v*$rata[$no];       
            $no++;  
		}				
	}  
    
	return $data;
}

function AHP_consistency_measure($matriks, $rata){
    $matriks = AHP_mmult($matriks, $rata);    
    foreach($matriks as $key => $value){
        $data[$key]=$value/$rata[$key];        
    }
    return $data;
}

function AHP_get_eigen_alternatif($kriteria=array()){
    $data = array();
    foreach($kriteria as $key => $value){
        $kode_kriteria = $key;
        $matriks = AHP_get_relalternatif($kode_kriteria);
        $total = AHP_get_total_kolom($matriks);
        $normal = AHP_normalize($matriks, $total);
        $rata = AHP_get_rata($normal);
        $data[$kode_kriteria] = $rata;                
    }
    $new = array();
    foreach($data as $key => $value){
        foreach($value as $k => $v){
            $new[$k][$key] = $v;
        }
    }
    return $new;
}

function AHP_get_rank($array){
    $data = $array;
    arsort($data);
    $no=1;
    $new = array();
    foreach($data as $key => $value){
        $new[$key] = $no++;
    }
    return $new;
}

function TOPSIS_get_hasil_analisa(){
    global $db;
    $rows = $db->get_results("SELECT a.kode_alternatif, k.kode_kriteria, ra.nilai
        FROM tb_alternatif a 
        	INNER JOIN tb_rel_alternatif ra ON ra.kode_alternatif=a.kode_alternatif
        	INNER JOIN tb_kriteria k ON k.kode_kriteria=ra.kode_kriteria
        ORDER BY a.kode_alternatif, k.kode_kriteria");
    $data = array();
    foreach($rows as $row){
        $data[$row->kode_alternatif][$row->kode_kriteria] = $row->nilai;
    }
    return $data;
}

function TOPSIS_hasil_analisa($echo=true){
    global $db, $ALTERNATIF, $KRITERIA;
    
    
    $data = TOPSIS_get_hasil_analisa();
    
    if(!$echo)
        return $data;
        
    $r.= "<tr><th></th>";   	
    $no=1;	
    foreach($data[key($data)] as $key => $value){
        $r.= "<th>".$KRITERIA[$key]['nama_kriteria']."</th>";
        $no++;      
    }    
    
    $no=1;	
    foreach($data as $key => $value){
        $r.= "<tr>";
        $r.= "<th nowrap>".$ALTERNATIF[$key]."</th>";
        foreach($value as $k => $v){
            $r.= "<td>".$v."</td>";
        }        
        $r.= "</tr>";
        $no++;    
    }    
    $r.= "</tr>";
    return $r;
}

function TOPSIS_nomalize($array, $max = true){
    $data = array();
    $kuadrat = array();
            
    foreach($array as $key => $value){     
        foreach($value as $k => $v){
            $kuadrat[$k]+= ($v * $v);           
        }                
    }    
    
    foreach($array as $key => $value){                
        foreach($value as $k => $v){
            $data[$key][$k] = $v / sqrt($kuadrat[$k]);
        }
    }
    return $data;
}

function TOPSIS_nomal_terbobot($array, $bobot){    
    $data = array();
    
    foreach($array as $key => $value){                
        foreach($value as $k => $v){
            $data[$key][$k] = $v * $bobot[$k];
        }
    }    
    
    return $data;
}

function TOPSIS_solusi_ideal($array){
    global $KRITERIA;
    $data = array();
    
    $temp = array();
    
    foreach($array as $key => $value){                
        foreach($value as $k => $v){
            $temp[$k][] = $v;
        }
    }    
    
    foreach($temp as $key => $value) {
        $max = max ($value);
        $min = min ($value);
        if($KRITERIA[$key]['atribut']=='benefit')
        {
            $data['positif'][$key] = $max;
            $data['negatif'][$key] = $min;
        }            
        else
        {
            $data['positif'][$key] = $min;
            $data['negatif'][$key] = $max;            
        }
    }
    
    return $data;
}

function TOPSIS_jarak_solusi($array, $ideal){    
    $temp = array();
    $arr = array();
    foreach($array as $key => $value){                
        foreach($value as $k => $v){
            $arr['positif'][$key][$k] = pow(($v - $ideal['positif'][$k]), 2);
            $arr['negatif'][$key][$k] = pow(($v - $ideal['negatif'][$k]), 2);
            
            $temp[$key]['positif']+= pow(($v - $ideal['positif'][$k]), 2);
            $temp[$key]['negatif']+= pow(($v - $ideal['negatif'][$k]), 2);
        }
        $temp[$key]['positif'] = sqrt($temp[$key]['positif']);
        $temp[$key]['negatif'] = sqrt($temp[$key]['negatif']);
    }        
    return $temp;
}

function TOPSIS_preferensi($array){
    global $KRITERIA;
    
    $temp = array();
    
    foreach($array as $key => $value){                
        $temp[$key] = $value['negatif'] / ($value['positif'] + $value['negatif']);
    }    
        
    return $temp;
}

function get_rank($array){
    $data = $array;
    arsort($data);
    $no=1;
    $new = array();
    foreach($data as $key => $value){
        $new[$key] = $no++;
    }
    return $new;
}