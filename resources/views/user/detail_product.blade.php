<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="{{ asset('css/user.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <title>Detail Produk | {{ $product->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

</head>
<body>

    <!-- Header -->
    @include('user.header')

    <!-- Detail Produk -->
    <div class="product-detail">
        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
        <div class="product-info">
            <h2>{{ $product->name }}</h2>
            <table>
                <tr>
                    <td>Harga Produk</td>
                    <td>
                        @if($product->price_max)
                            <strong>Rp {{ number_format($product->price_min, 0, ',', '.') }} - Rp {{ number_format($product->price_max, 0, ',', '.') }}</strong>
                        @else
                            <strong>Rp {{ number_format($product->price_min, 0, ',', '.') }}</strong>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Provinsi</td>
                    <td>Bali</td>
                </tr>
                <tr>
                    <td>Kota/Kabupaten</td>
                    <td>Bangli</td>
                </tr>
                <tr>
                    <td>Rating</td>
                    <td>
                        @if (round($product->ratings_avg_rating) <= 0)
                            Belum ada rating dari pelanggan
                        @else
                            {{ round($product->ratings_avg_rating) }}/5
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Link Pembelian</td>
                    <td>
                        <a href="{{ $product->tiktok_link }}" target="_blank" 
                           style="color: #ff0050; font-weight: bold; text-decoration: none;">
                           Beli di TikTok Shop
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Deskripsi</td>
                    <td>{{ $product->description }}</td>
                </tr>
            </table>

            <!-- Tombol Pesan Produk -->
            <div class="pesan-container">
                <a href="https://wa.me/6282144796279?text=Halo%20KEDIBIHI!%0A%0ASaya%20ingin%20memesan%20produk%20ini:%0A{{ urlencode($product->name) }}%0A%0AApakah%20bisa%20dibantu?"
   target="_blank"
   class="btn-pesan">
   Pesan Produk
</a>
            </div>
        </div>
    </div>

    <!-- Komentar dan Rating -->
    <section class="ulasan-produk">
        <h3>({{ $product->ratings_count }}) Ulasan Produk</h3>
        <div class="daftar-ulasan" id="daftarUlasan">
            @forelse ($product->ratings as $review )
                <div class="ulasan-item">
                    <p><strong>{{ $review->reviewer_name }}</strong> {{ str_repeat('⭐', $review->rating) }}</p>
                    <p>{{ $review->comment }}</p>
                </div>
            @empty
                <h3>Belum ada ulasan untuk produk ini</h3>
            @endforelse
        </div>

        @if ($errors->any())
            <div class="error-box">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="form-ulasan" action="{{ route('review.store', $product) }}" method="POST">
            @csrf
            <label for="nama">Nama Anda</label>
            <input type="text" id="nama" name="reviewer_name" placeholder="Nama Anda" required>

            <label for="rating">Rating</label>
            <select id="rating" name="rating" required>
                <option value="">Pilih rating</option>
                <option value=1>⭐</option>
                <option value=2>⭐⭐</option>
                <option value=3>⭐⭐⭐</option>
                <option value=4>⭐⭐⭐⭐</option>
                <option value=5>⭐⭐⭐⭐⭐</option>
            </select>

            <label for="komentar">Komentar</label>
            <textarea id="komentar" rows="4" name="comment" placeholder="Tulis komentar Anda..." required></textarea>

            <button type="submit">Kirim Ulasan</button>
        </form>
    </section>

    <!-- Footer -->
    @include('user.footer')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @if(session('success'))
                Swal.fire({
                    title: "Berhasil!",
                    text: "{{ session('success') }}",
                    icon: "success"
                });
            @endif
        });
    </script>

</body>
</html>
