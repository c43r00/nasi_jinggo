<?php
class KasirController {

    public function index(){
        $menu = [
            ["nama"=>"Nasi Jinggo Ayam","harga"=>8000],
            ["nama"=>"Nasi Jinggo Telur","harga"=>6000],
            ["nama"=>"Nasi Jinggo Udang","harga"=>10000],
            ["nama"=>"Nasi Jinggo Ikan","harga"=>9000],
            ["nama"=>"Nasi Jinggo Tempe","harga"=>5000],
            ["nama"=>"Nasi Jinggo Tahu","harga"=>5000],
            ["nama"=>"Nasi Jinggo Combo","harga"=>12000],
        ];

        include "views/kasir.php";
    }

    public function simpan(){
        $menu = $_POST['menu'];
        $qty = $_POST['qty'];
        $total = $_POST['total'];
        $metode = $_POST['metode'];

        include "config/database.php";

        $conn->query("INSERT INTO transaksi(total, metode_pembayaran) 
                      VALUES ('$total','$metode')");

        $id_transaksi = $conn->insert_id;

        for($i=0;$i<count($menu);$i++){
            $conn->query("INSERT INTO detail_transaksi(id_transaksi, menu, qty) 
                          VALUES ('$id_transaksi','$menu[$i]','$qty[$i]')");
        }

        header("Location: index.php?page=kasir");
    }
}
