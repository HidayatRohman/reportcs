<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
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
}

interface Props {
    transaksi: Transaksi;
    produks: Produk[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Data Transaksi',
        href: '/transaksi',
    },
    {
        title: 'Edit Transaksi',
        href: `/transaksi/${props.transaksi.id}/edit`,
    },
];

const form = useForm({
    nama: props.transaksi.nama,
    no_tlp: props.transaksi.no_tlp,
    produk_id: props.transaksi.produk_id.toString(),
    chat: props.transaksi.chat,
    provinsi: props.transaksi.provinsi,
    kota: props.transaksi.kota,
    transaksi: props.transaksi.transaksi.toString(),
    keterangan: props.transaksi.keterangan,
    komentar: props.transaksi.komentar || '',
    _method: 'PUT',
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

const transaksiDisplay = ref(formatCurrencyInput(props.transaksi.transaksi.toString()));

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
    form.post(`/transaksi/${props.transaksi.id}`);
};
</script>

<template>
    <Head title="Edit Transaksi" />
    
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
                            <CardTitle>Edit Transaksi</CardTitle>
                            <CardDescription>
                                Perbarui informasi transaksi
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
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
                                    class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 dark:bg-background dark:text-foreground dark:border-input dark:[&>option]:bg-background dark:[&>option]:text-foreground [&>option]:bg-background [&>option]:text-foreground [&>option]:py-2"
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
                                    class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 dark:bg-background dark:text-foreground dark:border-input dark:[&>option]:bg-background dark:[&>option]:text-foreground [&>option]:bg-background [&>option]:text-foreground [&>option]:py-2"
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
                                    class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 dark:bg-background dark:text-foreground dark:border-input dark:[&>option]:bg-background dark:[&>option]:text-foreground [&>option]:bg-background [&>option]:text-foreground [&>option]:py-2"
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

                        <div class="flex justify-end space-x-2">
                            <Button type="button" variant="outline" as-child>
                                <a href="/transaksi">Batal</a>
                            </Button>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
