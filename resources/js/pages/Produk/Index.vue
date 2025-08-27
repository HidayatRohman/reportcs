<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuTrigger } from '@/components/ui/dropdown-menu';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { Plus, MoreHorizontal, Eye, Edit, Trash2 } from 'lucide-vue-next';
import { ref, computed } from 'vue';

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

const isDialogOpen = ref(false);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data Produk',
        href: '/produk',
    },
];

const form = useForm({
    nama_produk: '',
    deskripsi: '',
    harga: '',
    kategori: '',
    status: 'aktif',
});

const kategoriOptions = [
    { value: 'Makanan', label: 'Makanan' },
    { value: 'Minuman', label: 'Minuman' },
    { value: 'Lainnya', label: 'Lainnya' },
];

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
};

const formatCurrencyInput = (value: string) => {
    // Remove non-numeric characters except decimal point
    const numericValue = value.replace(/[^\d.]/g, '');
    
    // Convert to number and format as currency
    const number = parseFloat(numericValue) || 0;
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
};

const hargaDisplay = ref('');

const updateHarga = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const value = target.value;
    
    // Remove currency formatting to get raw number
    const numericValue = value.replace(/[^\d]/g, '');
    form.harga = numericValue;
    
    // Update display value with currency formatting
    if (numericValue) {
        hargaDisplay.value = formatCurrencyInput(numericValue);
    } else {
        hargaDisplay.value = '';
    }
};

const submit = () => {
    form.post('/produk', {
        onSuccess: () => {
            isDialogOpen.value = false;
            form.reset();
            hargaDisplay.value = '';
        },
    });
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
                        <Dialog v-model:open="isDialogOpen">
                            <DialogTrigger as-child>
                                <Button>
                                    <Plus class="w-4 h-4 mr-2" />
                                    Tambah Produk
                                </Button>
                            </DialogTrigger>
                            <DialogContent class="sm:max-w-[600px]">
                                <DialogHeader>
                                    <DialogTitle>Tambah Produk</DialogTitle>
                                    <DialogDescription>
                                        Tambahkan produk baru ke dalam sistem
                                    </DialogDescription>
                                </DialogHeader>
                                <form @submit.prevent="submit" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <Label for="nama_produk">Nama Produk</Label>
                                            <Input
                                                id="nama_produk"
                                                v-model="form.nama_produk"
                                                type="text"
                                                placeholder="Masukkan nama produk"
                                                required
                                            />
                                            <InputError :message="form.errors.nama_produk" />
                                        </div>

                                        <div class="space-y-2">
                                            <Label for="kategori">Kategori</Label>
                                            <select 
                                                id="kategori"
                                                v-model="form.kategori"
                                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                                required
                                            >
                                                <option value="">Pilih Kategori</option>
                                                <option v-for="kategori in kategoriOptions" :key="kategori.value" :value="kategori.value">
                                                    {{ kategori.label }}
                                                </option>
                                            </select>
                                            <InputError :message="form.errors.kategori" />
                                        </div>

                                        <div class="space-y-2">
                                            <Label for="harga">Harga</Label>
                                            <Input
                                                id="harga"
                                                :value="hargaDisplay"
                                                @input="updateHarga"
                                                type="text"
                                                placeholder="Rp 0"
                                                required
                                            />
                                            <InputError :message="form.errors.harga" />
                                        </div>

                                        <div class="space-y-2">
                                            <Label for="status">Status</Label>
                                            <select 
                                                id="status"
                                                v-model="form.status"
                                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                            >
                                                <option value="aktif">Aktif</option>
                                                <option value="nonaktif">Non Aktif</option>
                                            </select>
                                            <InputError :message="form.errors.status" />
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="deskripsi">Deskripsi</Label>
                                        <textarea
                                            id="deskripsi"
                                            v-model="form.deskripsi"
                                            placeholder="Masukkan deskripsi produk (opsional)"
                                            rows="4"
                                            class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                        ></textarea>
                                        <InputError :message="form.errors.deskripsi" />
                                    </div>

                                    <DialogFooter>
                                        <Button type="button" variant="outline" @click="isDialogOpen = false">
                                            Batal
                                        </Button>
                                        <Button type="submit" :disabled="form.processing">
                                            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                                        </Button>
                                    </DialogFooter>
                                </form>
                            </DialogContent>
                        </Dialog>
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
