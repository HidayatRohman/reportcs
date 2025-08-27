<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Edit } from 'lucide-vue-next';

interface Produk {
    id: number;
    nama_produk: string;
    deskripsi: string;
    harga: number;
    stok: number;
    kategori: string;
    status: string;
    created_at: string;
    updated_at: string;
}

interface Props {
    produk: Produk;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data Produk',
        href: '/produk',
    },
    {
        title: 'Detail Produk',
        href: `/produk/${props.produk.id}`,
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
    <Head title="Detail Produk" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 p-4">
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <Button variant="outline" size="icon" as-child>
                                <Link href="/produk">
                                    <ArrowLeft class="h-4 w-4" />
                                </Link>
                            </Button>
                            <div>
                                <CardTitle>{{ produk.nama_produk }}</CardTitle>
                                <CardDescription>
                                    Detail informasi produk
                                </CardDescription>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <Button variant="outline" as-child>
                                <Link :href="`/produk/${produk.id}/edit`">
                                    <Edit class="w-4 h-4 mr-2" />
                                    Edit
                                </Link>
                            </Button>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-muted-foreground">Nama Produk</h3>
                                <p class="text-lg font-semibold">{{ produk.nama_produk }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-muted-foreground">Kategori</h3>
                                <p>{{ produk.kategori }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-muted-foreground">Harga</h3>
                                <p class="text-lg font-semibold text-green-600">{{ formatCurrency(produk.harga) }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-muted-foreground">Stok</h3>
                                <p class="text-lg">{{ produk.stok }} unit</p>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <h3 class="text-sm font-medium text-muted-foreground">Status</h3>
                                <span :class="[
                                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                    produk.status === 'aktif' 
                                        ? 'bg-green-100 text-green-800' 
                                        : 'bg-gray-100 text-gray-800'
                                ]">
                                    {{ produk.status }}
                                </span>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-muted-foreground">Dibuat pada</h3>
                                <p>{{ formatDate(produk.created_at) }}</p>
                            </div>
                            
                            <div>
                                <h3 class="text-sm font-medium text-muted-foreground">Terakhir diperbarui</h3>
                                <p>{{ formatDate(produk.updated_at) }}</p>
                            </div>
                        </div>
                        
                        <div v-if="produk.deskripsi" class="md:col-span-2 space-y-2">
                            <h3 class="text-sm font-medium text-muted-foreground">Deskripsi</h3>
                            <p class="text-sm leading-relaxed">{{ produk.deskripsi }}</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
