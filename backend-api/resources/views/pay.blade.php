<!DOCTYPE html>
<html>
<head>
    <title>Bayar Pesanan</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>
<body>

<h2>Halaman Pembayaran</h2>

<button id="pay-button">Bayar Sekarang</button>

<script>
document.getElementById('pay-button').onclick = function () {
    snap.pay('{{ $token }}', {
        onSuccess: function(result){
            alert("Pembayaran berhasil!");
            console.log(result);
        },
        onPending: function(result){
            alert("Menunggu pembayaran!");
            console.log(result);
        },
        onError: function(result){
            alert("Pembayaran gagal!");
            console.log(result);
        },
        onClose: function(){
            alert('Kamu menutup popup sebelum bayar');
        }
    });
};
</script>

</body>
</html>