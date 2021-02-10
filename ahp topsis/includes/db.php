<?php
/**
 * Class database MySQL
 */
class DB{
    /**
     * menyimpan koneksi database
     */
    var $conn = null;  
    /**
     * id auto increment
     */ 
    public $insert_id = 0;
    /**
     * membuat koneksi ke database
     * @param string $host Alamat host/server MySQL
     * @param string $username Username server MySQL
     * @param string $passwd Password server MySQL
     * @param string $dbname Nama database MySQL
     */
    public function __construct($host, $username, $passwd, $dbname){
        $this->conn = mysqli_connect($host, $username, $passwd, $dbname);
    }
    /**
     * Mengeksekusi perintah SQL
     * @param string $sql Perintah SQL yang ingin dieksekusi
     * @return resource Hasil query
     */
    public function query($sql){
        $query = mysqli_query($this->conn, $sql) or die('<pre>Error mysqli_query: ' . mysqli_error($this->conn) . '<br />' . $sql . '</pre>');
        
        //jika query mengandung perintah insert atau replace
        if ( preg_match("/^(insert|replace)\s+/i",$sql) )
		{
			$this->insert_id = @$this->conn->insert_id;
		}        
        return $query;
    }
    /**
     * Mengambil 1 baris data ke dari perintah SQL
     * @param string $sql Perintah SQL
     * @return object Satu baris data
     */
    public function get_row($sql){
        $query = $this->query($sql);
        return mysqli_fetch_object($query);
    }
    /**
     * Mengambil data ke dari perintah SQL
     * @param string $sql Perintah SQL
     * @return array Data dalam bentuk array object
     */
    public function get_results($sql){
        $query = $this->query($sql);
        $arr = array();
        while($row = mysqli_fetch_object($query)){
            $arr[] = $row;
        }
        return $arr;
    }
    /**
     * Mengambil 1 kolom data ke dari perintah SQL
     * @param string $sql Perintah SQL
     * @return object Satu kolom data
     */
    public function get_var($sql){
        $query = $this->query($sql);
        $row = mysqli_fetch_row($query);
        return $row[0];
    }
}