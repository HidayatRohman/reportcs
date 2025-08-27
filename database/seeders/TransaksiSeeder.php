<?php

namespace Database\Seeders;

use App\Models\Transaksi;
use App\Models\Produk;
use Illuminate\Database\Seeder;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $produks = Produk::all();
        
        if ($produks->isEmpty()) {
            $this->command->warn('Tidak ada produk. Jalankan ProdukSeeder terlebih dahulu.');
            return;
        }

        $transaksiData = [
            [
                'nama' => 'John Doe',
                'no_tlp' => '081234567890',
                'produk_id' => $produks->random()->id,
                'chat' => 'Baru',
                'provinsi' => 'DKI Jakarta',
                'kota' => 'Jakarta Selatan',
                'transaksi' => 150000,
                'keterangan' => 'Aktif',
                'komentar' => 'Pelanggan baru, respon cepat',
            ],
            [
                'nama' => 'Jane Smith',
                'no_tlp' => '085987654321',
                'produk_id' => $produks->random()->id,
                'chat' => 'Followup',
                'provinsi' => 'Jawa Barat',
                'kota' => 'Bandung',
                'transaksi' => 75000,
                'keterangan' => 'Off Sementara',
                'komentar' => 'Perlu follow up lagi minggu depan',
            ],
            [
                'nama' => 'Ahmad Rahman',
                'no_tlp' => '087777888999',
                'produk_id' => $produks->random()->id,
                'chat' => 'Baru',
                'provinsi' => 'Jawa Timur',
                'kota' => 'Surabaya',
                'transaksi' => 200000,
                'keterangan' => 'Otw Open',
                'komentar' => 'Tertarik dengan produk premium',
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'no_tlp' => '081122334455',
                'produk_id' => $produks->random()->id,
                'chat' => 'Followup',
                'provinsi' => 'Jawa Tengah',
                'kota' => 'Semarang',
                'transaksi' => 50000,
                'keterangan' => 'Tutup',
                'komentar' => 'Tidak jadi beli, budget terbatas',
            ],
            [
                'nama' => 'Budi Santoso',
                'no_tlp' => '089966554433',
                'produk_id' => $produks->random()->id,
                'chat' => 'Baru',
                'provinsi' => 'Bali',
                'kota' => 'Denpasar',
                'transaksi' => 125000,
                'keterangan' => 'Aktif',
                'komentar' => 'Akan order dalam 2 hari',
            ],
        ];

        foreach ($transaksiData as $data) {
            Transaksi::create($data);
        }
    }
}
