<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';

interface Produk {
    id: number;
    nama_produk: string;
    harga: number;
    kategori: string;
    status: string;
}

interface Transaksi {
    id: number;
    nama: string;
    no_tlp: string;
    produk_id: number;
    produk: Produk;
    chat: string;
    provinsi: string;
    kota: string;
    transaksi: number;
    keterangan: string;
    komentar: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    transaksi: Transaksi;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data Transaksi',
        href: '/transaksi',
    },
    {
        title: 'Detail Transaksi',
        href: `/transaksi/${props.transaksi.id}`,
    },
];

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};
</script>

<template>
    <Head title="Detail Transaksi" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 p-4">
            <Card>
                <CardHeader>
                    <div class="flex items-center gap-4">
                        <Button variant="outline" size="icon" as-child>
                            <a href="/transaksi">
                                <ArrowLeft class="h-4 w-4" />
                            </a>
                        </Button>
                        <div>
                            <CardTitle>Detail Transaksi</CardTitle>
                            <CardDescription>
                                Informasi lengkap transaksi
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Informasi Pelanggan</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Nama:</span>
                                        <span class="font-medium">{{ transaksi.nama }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">No. Telepon:</span>
                                        <span class="font-medium">{{ transaksi.no_tlp }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Chat:</span>
                                        <span :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            transaksi.chat === 'Baru' 
                                                ? 'bg-blue-100 text-blue-800' 
                                                : 'bg-orange-100 text-orange-800'
                                        ]">
                                            {{ transaksi.chat }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-2">Lokasi</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Provinsi:</span>
                                        <span class="font-medium">{{ transaksi.provinsi }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Kota:</span>
                                        <span class="font-medium">{{ transaksi.kota }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Informasi Produk</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Produk:</span>
                                        <span class="font-medium">{{ transaksi.produk.nama_produk }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Kategori:</span>
                                        <span class="font-medium">{{ transaksi.produk.kategori }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Harga Produk:</span>
                                        <span class="font-medium">{{ formatCurrency(transaksi.produk.harga) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-semibold mb-2">Transaksi</h3>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Nilai Transaksi:</span>
                                        <span class="font-medium text-lg">{{ formatCurrency(transaksi.transaksi) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Keterangan:</span>
                                        <span :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            transaksi.keterangan === 'Aktif' ? 'bg-green-100 text-green-800' :
                                            transaksi.keterangan === 'Off Sementara' ? 'bg-yellow-100 text-yellow-800' :
                                            transaksi.keterangan === 'Otw Open' ? 'bg-blue-100 text-blue-800' :
                                            transaksi.keterangan === 'Tutup' ? 'bg-red-100 text-red-800' :
                                            'bg-gray-100 text-gray-800'
                                        ]">
                                            {{ transaksi.keterangan }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-semibold mb-2">Komentar</h3>
                        <div class="bg-muted p-4 rounded-lg">
                            <p class="text-sm">{{ transaksi.komentar || 'Tidak ada komentar' }}</p>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t">
                        <h3 class="text-lg font-semibold mb-2">Informasi Waktu</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Dibuat:</span>
                                <span class="font-medium">{{ formatDate(transaksi.created_at) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Diperbarui:</span>
                                <span class="font-medium">{{ formatDate(transaksi.updated_at) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-2 mt-6">
                        <Button variant="outline" as-child>
                            <a href="/transaksi">Kembali</a>
                        </Button>
                        <Button as-child>
                            <a :href="`/transaksi/${transaksi.id}/edit`">Edit Transaksi</a>
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
