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
import { ref } from 'vue';

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
    transaksis: {
        data: Transaksi[];
        links: any[];
        meta: any;
    };
    produks: Produk[];
}

defineProps<Props>();

const isDialogOpen = ref(false);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data Transaksi',
        href: '/transaksi',
    },
];

const form = useForm({
    nama: '',
    no_tlp: '',
    produk_id: '',
    chat: '',
    provinsi: '',
    kota: '',
    transaksi: '',
    keterangan: '',
    komentar: '',
});

const chatOptions = [
    { value: 'Baru', label: 'Baru' },
    { value: 'Followup', label: 'Followup' },
];

const keteranganOptions = [
    { value: 'Aktif', label: 'Aktif' },
    { value: 'Off Sementara', label: 'Off Sementara' },
    { value: 'Otw Open', label: 'Otw Open' },
    { value: 'Tutup', label: 'Tutup' },
    { value: 'Lainnya', label: 'Lainnya' },
];

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
};

const formatCurrencyInput = (value: string) => {
    const numericValue = value.replace(/[^\d.]/g, '');
    const number = parseFloat(numericValue) || 0;
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(number);
};

const transaksiDisplay = ref('');

const updateTransaksi = (event: Event) => {
    const target = event.target as HTMLInputElement;
    const value = target.value;
    
    const numericValue = value.replace(/[^\d]/g, '');
    form.transaksi = numericValue;
    
    if (numericValue) {
        transaksiDisplay.value = formatCurrencyInput(numericValue);
    } else {
        transaksiDisplay.value = '';
    }
};

const submit = () => {
    form.post('/transaksi', {
        onSuccess: () => {
            isDialogOpen.value = false;
            form.reset();
            transaksiDisplay.value = '';
        },
    });
};

const deleteTransaksi = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus transaksi ini?')) {
        router.delete(`/transaksi/${id}`);
    }
};
</script>

<template>
    <Head title="Data Transaksi" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 p-4">
            <Card>
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Data Transaksi</CardTitle>
                            <CardDescription>
                                Kelola data transaksi Anda
                            </CardDescription>
                        </div>
                        <Dialog v-model:open="isDialogOpen">
                            <DialogTrigger as-child>
                                <Button>
                                    <Plus class="w-4 h-4 mr-2" />
                                    Tambah Transaksi
                                </Button>
                            </DialogTrigger>
                            <DialogContent class="sm:max-w-[700px] max-h-[90vh] overflow-y-auto">
                                <DialogHeader>
                                    <DialogTitle>Tambah Transaksi</DialogTitle>
                                    <DialogDescription>
                                        Tambahkan transaksi baru ke dalam sistem
                                    </DialogDescription>
                                </DialogHeader>
                                <form @submit.prevent="submit" class="space-y-6">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <Label for="nama">Nama</Label>
                                            <Input
                                                id="nama"
                                                v-model="form.nama"
                                                type="text"
                                                placeholder="Masukkan nama"
                                                required
                                            />
                                            <InputError :message="form.errors.nama" />
                                        </div>

                                        <div class="space-y-2">
                                            <Label for="no_tlp">No. Telepon</Label>
                                            <Input
                                                id="no_tlp"
                                                v-model="form.no_tlp"
                                                type="text"
                                                placeholder="Masukkan no. telepon"
                                                required
                                            />
                                            <InputError :message="form.errors.no_tlp" />
                                        </div>

                                        <div class="space-y-2">
                                            <Label for="produk_id">Produk</Label>
                                            <select 
                                                id="produk_id"
                                                v-model="form.produk_id"
                                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                                required
                                            >
                                                <option value="">Pilih Produk</option>
                                                <option v-for="produk in produks" :key="produk.id" :value="produk.id">
                                                    {{ produk.nama_produk }} - {{ formatCurrency(produk.harga) }}
                                                </option>
                                            </select>
                                            <InputError :message="form.errors.produk_id" />
                                        </div>

                                        <div class="space-y-2">
                                            <Label for="chat">Chat</Label>
                                            <select 
                                                id="chat"
                                                v-model="form.chat"
                                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                                required
                                            >
                                                <option value="">Pilih Chat</option>
                                                <option v-for="chat in chatOptions" :key="chat.value" :value="chat.value">
                                                    {{ chat.label }}
                                                </option>
                                            </select>
                                            <InputError :message="form.errors.chat" />
                                        </div>

                                        <div class="space-y-2">
                                            <Label for="provinsi">Provinsi</Label>
                                            <Input
                                                id="provinsi"
                                                v-model="form.provinsi"
                                                type="text"
                                                placeholder="Masukkan provinsi"
                                                required
                                            />
                                            <InputError :message="form.errors.provinsi" />
                                        </div>

                                        <div class="space-y-2">
                                            <Label for="kota">Kota</Label>
                                            <Input
                                                id="kota"
                                                v-model="form.kota"
                                                type="text"
                                                placeholder="Masukkan kota"
                                                required
                                            />
                                            <InputError :message="form.errors.kota" />
                                        </div>

                                        <div class="space-y-2">
                                            <Label for="transaksi">Transaksi</Label>
                                            <Input
                                                id="transaksi"
                                                :value="transaksiDisplay"
                                                @input="updateTransaksi"
                                                type="text"
                                                placeholder="Rp 0"
                                                required
                                            />
                                            <InputError :message="form.errors.transaksi" />
                                        </div>

                                        <div class="space-y-2">
                                            <Label for="keterangan">Keterangan</Label>
                                            <select 
                                                id="keterangan"
                                                v-model="form.keterangan"
                                                class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                                required
                                            >
                                                <option value="">Pilih Keterangan</option>
                                                <option v-for="keterangan in keteranganOptions" :key="keterangan.value" :value="keterangan.value">
                                                    {{ keterangan.label }}
                                                </option>
                                            </select>
                                            <InputError :message="form.errors.keterangan" />
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="komentar">Komentar</Label>
                                        <textarea
                                            id="komentar"
                                            v-model="form.komentar"
                                            placeholder="Masukkan komentar (opsional)"
                                            rows="4"
                                            class="flex min-h-[60px] w-full rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                        ></textarea>
                                        <InputError :message="form.errors.komentar" />
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
                                    <th class="text-left py-3 px-4 font-medium">Nama</th>
                                    <th class="text-left py-3 px-4 font-medium">No. Telepon</th>
                                    <th class="text-left py-3 px-4 font-medium">Produk</th>
                                    <th class="text-left py-3 px-4 font-medium">Chat</th>
                                    <th class="text-left py-3 px-4 font-medium">Lokasi</th>
                                    <th class="text-left py-3 px-4 font-medium">Transaksi</th>
                                    <th class="text-left py-3 px-4 font-medium">Keterangan</th>
                                    <th class="text-right py-3 px-4 font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="transaksi in transaksis.data" :key="transaksi.id" class="border-b">
                                    <td class="py-3 px-4">
                                        <div>
                                            <div class="font-semibold">{{ transaksi.nama }}</div>
                                            <div class="text-sm text-muted-foreground">{{ transaksi.komentar || 'Tanpa komentar' }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">{{ transaksi.no_tlp }}</td>
                                    <td class="py-3 px-4">
                                        <div>
                                            <div class="font-medium">{{ transaksi.produk.nama_produk }}</div>
                                            <div class="text-sm text-muted-foreground">{{ formatCurrency(transaksi.produk.harga) }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <span :class="[
                                            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                            transaksi.chat === 'Baru' 
                                                ? 'bg-blue-100 text-blue-800' 
                                                : 'bg-orange-100 text-orange-800'
                                        ]">
                                            {{ transaksi.chat }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div>
                                            <div class="font-medium">{{ transaksi.kota }}</div>
                                            <div class="text-sm text-muted-foreground">{{ transaksi.provinsi }}</div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">{{ formatCurrency(transaksi.transaksi) }}</td>
                                    <td class="py-3 px-4">
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
                                                    <Link :href="`/transaksi/${transaksi.id}`">
                                                        <Eye class="w-4 h-4 mr-2" />
                                                        Lihat
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem as-child>
                                                    <Link :href="`/transaksi/${transaksi.id}/edit`">
                                                        <Edit class="w-4 h-4 mr-2" />
                                                        Edit
                                                    </Link>
                                                </DropdownMenuItem>
                                                <DropdownMenuItem @click="deleteTransaksi(transaksi.id)">
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
