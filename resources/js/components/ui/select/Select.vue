<script setup lang="ts">
import { cn } from '@/lib/utils'

interface Props {
  id?: string
  name?: string
  modelValue?: string | number
  placeholder?: string
  required?: boolean
  disabled?: boolean
  class?: string
}

interface Emits {
  (e: 'update:modelValue', value: string): void
  (e: 'change', event: Event): void
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Select an option',
})

const emit = defineEmits<Emits>()

const handleChange = (event: Event) => {
  const target = event.target as HTMLSelectElement
  emit('update:modelValue', target.value)
  emit('change', event)
}
</script>

<template>
  <select 
    :id="id"
    :name="name"
    :value="modelValue"
    :required="required"
    :disabled="disabled"
    @change="handleChange"
    :class="cn(
      'flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50',
      'dark:bg-background dark:text-foreground dark:border-input',
      'dark:[&>option]:bg-background dark:[&>option]:text-foreground',
      '[&>option]:bg-background [&>option]:text-foreground [&>option]:py-2',
      props.class
    )"
  >
    <slot />
  </select>
</template>
