<h2>Kasir - Nasi Jinggo Apel</h2>

<form method="post" action="index.php?page=kasir&aksi=simpan">

<table border="1" width="100%">
<tr><th>Menu</th><th>Harga</th><th>Qty</th></tr>

<?php foreach($menu as $m): ?>
<tr>
    <td><?= $m['nama'] ?></td>
    <td>Rp <?= $m['harga'] ?></td>
    <td>
        <input type="number" name="qty[]" value="0" min="0" 
        onkeyup="hitung()" data-harga="<?= $m['harga'] ?>">
        <input type="hidden" name="menu[]" value="<?= $m['nama'] ?>">
    </td>
</tr>
<?php endforeach; ?>

</table>

<h3>Total: Rp <span id="total">0</span></h3>
<input type="hidden" name="total" id="totalInput">

<select name="metode" required>
    <option value="">-- Pilih Pembayaran --</option>
    <option>Cash</option>
    <option>QRIS</option>
    <option>Transfer</option>
</select>

<br><br>
<button type="submit">Simpan Transaksi</button>
</form>

<script>
function hitung(){
    let total = 0;
    document.querySelectorAll("input[type=number]").forEach(el=>{
        let harga = el.dataset.harga;
        total += el.value * harga;
    });
    document.getElementById("total").innerText = total;
    document.getElementById("totalInput").value = total;
}
</script>
