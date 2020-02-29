<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('invoices')">Invoices</inertia-link>
      <span class="text-indigo-400 font-medium">/</span> Create
    </h1>
    <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="submit">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.amount" type="number" :errors="$page.errors.amount" class="pr-6 pb-8 w-full lg:w-1/2" label="Amount" />
          <select-input v-model="form.currency" :errors="$page.errors.currency" class="pr-6 pb-8 w-full lg:w-1/2" label="Currency">
            <option :value="null" />
            <option value="NGN">Naira</option>
          </select-input>
          <select-input v-model="form.customer_id" :errors="$page.errors.customer_id" class="pr-6 pb-8 w-full lg:w-1/2" label="Customer">
            <option :value="null" />
            <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
          </select-input>
          <text-input v-model="form.due_date" type="date" :errors="$page.errors.due_date" class="pr-6 pb-8 w-full lg:w-1/2" label="Due Date" />
          <textarea-input v-model="form.description" :errors="$page.errors.description" class="pr-6 pb-8 w-full" label="Description" />
        </div>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex justify-end items-center">
          <loading-button :loading="sending" class="btn-indigo" type="submit">Create Invoice</loading-button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import TextareaInput from '@/Shared/TextareaInput'

export default {
  metaInfo: { title: 'Create Invoice' },
  layout: Layout,
  components: {
    LoadingButton,
    TextareaInput,
    SelectInput,
    TextInput,
  },
  props: {
    customers: Array,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
        customer_id: null,
        amount: null,
        description: null,
        currency: null,
        due_date: null,
      },
    }
  },
  methods: {
    submit() {
      this.sending = true
      this.$inertia.post(this.route('invoices.store'), this.form)
        .then(() => this.sending = false)
    },
  },
}
</script>
