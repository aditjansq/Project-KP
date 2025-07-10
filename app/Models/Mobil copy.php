
// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use App\Models\Transaksi; // Tambahkan ini untuk mengimpor model Transaksi
// use App\Models\Servis; // Pastikan ini juga ada jika belum

// class Mobil extends Model
// {
//     use HasFactory;

//     // Menentukan kolom yang bisa diisi (mass assignable)
//     protected $fillable = [
//         'kode_mobil',
//         'jenis_mobil', // Kolom 'jenis_mobil'
//         'tipe_mobil',
//         'merek_mobil',
//         'tahun_pembuatan',
//         'warna_mobil',
//         'harga_mobil',
//         'bahan_bakar',
//         'transmisi', // Kolom 'transmisi'
//         'nomor_polisi',
//         'nomor_rangka',
//         'nomor_mesin',
//         'nomor_bpkb',
//         'tanggal_masuk',
//         'status_mobil',
//         'ketersediaan',
//         'masa_berlaku_pajak'
//     ];

//     // Menentukan kolom yang diperlakukan sebagai tanggal oleh Eloquent
//     protected $dates = ['tanggal_masuk', 'masa_berlaku_pajak'];

//     /**
//      * Relasi satu ke banyak dengan Servis.
//      * Satu mobil dapat memiliki banyak record servis.
//      */
//     public function servis()
//     {
//         return $this->hasMany(Servis::class);
//     }

//     /**
//      * Relasi satu ke banyak dengan Transaksi.
//      * Satu mobil dapat memiliki banyak record transaksi.
//      */
//     public function transaksis()
//     {
//         return $this->hasMany(Transaksi::class, 'mobil_id'); // 'mobil_id' adalah foreign key di tabel transaksis
//     }

//     /**
//      * Relasi banyak ke banyak dengan Pelanggan.
//      * Banyak mobil dapat dimiliki oleh banyak pelanggan melalui tabel pivot 'mobil_pelanggan'.
//      */
//     public function pelanggan()
//     {
//         return $this->belongsToMany(Pelanggan::class, 'mobil_pelanggan');
//     }
// }
