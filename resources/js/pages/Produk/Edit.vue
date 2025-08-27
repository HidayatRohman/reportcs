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
    deskripsi: string;
    harga: number;
    kategori: string;
    status: string;
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
        title: 'Edit Produk',
        href: `/produk/${props.produk.id}/edit`,
    },
];

const form = useForm({
    nama_produk: props.produk.nama_produk,
    deskripsi: props.produk.deskripsi || '',
    harga: props.produk.harga.toString(),
    kategori: props.produk.kategori,
    status: props.produk.status,
    _method: 'PUT',
});

const kategoriOptions = [
    { value: 'Makanan', label: 'Makanan' },
    { value: 'Minuman', label: 'Minuman' },
    { value: 'Lainnya', label: 'Lainnya' },
];

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

const hargaDisplay = ref(formatCurrencyInput(props.produk.harga.toString()));

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
    form.post(`/produk/${props.produk.id}`);
};
</script>

<template>
    <Head title="Edit Produk" />
    
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-1 flex-col gap-4 p-4">
            <Card>
                <CardHeader>
                    <div class="flex items-center gap-4">
                        <Button variant="outline" size="icon" as-child>
                            <a href="/produk">
                                <ArrowLeft class="h-4 w-4" />
                            </a>
                        </Button>
                        <div>
                            <CardTitle>Edit Produk</CardTitle>
                            <CardDescription>
                                Perbarui informasi produk
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent>
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
                                    class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 dark:bg-background dark:text-foreground dark:border-input dark:[&>option]:bg-background dark:[&>option]:text-foreground [&>option]:bg-background [&>option]:text-foreground [&>option]:py-2"
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

                            <div class="space-y-2 md:col-span-2">
                                <Label for="status">Status</Label>
                                <select 
                                    id="status"
                                    v-model="form.status"
                                    class="flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
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

                        <div class="flex justify-end space-x-2">
                            <Button type="button" variant="outline" as-child>
                                <a href="/produk">Batal</a>
                            </Button>
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Menyimpan...' : 'Perbarui' }}
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
