<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, MoreHorizontal, Eye, Edit, Trash2 } from 'lucide-vue-next';

interface Produk {
    id: number;
    nama_produk: string;
    deskripsi: string;
    harga: number;
    kategori: string;
    status: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    produks: {
        data: Produk[];
        links: any[];
        meta: any;
    };
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data Produk',
        href: '/produk',
    },
];

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
};

const deleteProduk = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        router.delete(`/produk/${id}`);
    }
};
</script>

<template>
    <Head title="Data Produk" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 p-4">
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Data Produk</CardTitle>
                            <CardDescription>
                                Kelola data produk Anda
                            </CardDescription>
                        </div>
                        <Link href="/produk/create">
                            <Button>
                                <Plus class="w-4 h-4 mr-2" />
                                Tambah Produk
                            </Button>
                        </Link>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left py-3 px-4 font-medium">Nama Produk</th>
                                    <th class="text-left py-3 px-4 font-medium">Kategori</th>
                                    <th class="text-left py-3 px-4 font-medium">Harga</th>
                                    <th class="text-left py-3 px-4 font-medium">Status</th>
                                    <th class="text-right py-3 px-4 font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="produk in produks.data" :key="produk.id" class="border-b">
                                    <td class="py-3 px-4">
                                        <div>
                                            <div class="font-semibold">{{ produk.nama_produk }}</div>
                                            <div class="text-sm text-muted-foreground">{{ produk.deskripsi }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">{{ produk.kategori }}</td>
                                    <td class="py-3 px-4">{{ formatCurrency(produk.harga) }}</td>
                                    <td class="py-3 px-4">{{ produk.stok }}</td>
                                    <td class="py-3 px-4">
                                        <span :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            produk.status === 'aktif' 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-gray-100 text-gray-800'
                                        ]">
                                            {{ produk.status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-right">
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button variant="ghost" class="h-8 w-8 p-0">
                                                    <MoreHorizontal class="h-4 w-4" />
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent align="end">
                                                <DropdownMenuItem as-child>
                                                    <Link :href="`/produk/${produk.id}`">
                                                        <Eye class="w-4 h-4 mr-2" />
                                                        Lihat
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem as-child>
                                                    <Link :href="`/produk/${produk.id}/edit`">
                                                        <Edit class="w-4 h-4 mr-2" />
                                                        Edit
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem @click="deleteProduk(produk.id)">
                                                    <Trash2 class="w-4 h-4 mr-2" />
                                                    Hapus
                                                </DropdownMenuItem>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
