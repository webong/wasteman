<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('invoices')">Invoices</inertia-link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ invoice.invoice_id }}
    </h1>
    <trashed-message v-if="invoice.deleted_at" class="mb-6" @restore="restore">
      This invoice has been deleted.
    </trashed-message>
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
          <!-- <text-input v-model="form.first_name" :errors="$page.errors.first_name" class="pr-6 pb-8 w-full lg:w-1/2" label="First name" />
          <text-input v-model="form.last_name" :errors="$page.errors.last_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Last name" />
          <select-input v-model="form.customer_id" :errors="$page.errors.customer_id" class="pr-6 pb-8 w-full lg:w-1/2" label="Customer">
            <option :value="null" />
            <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{ customer.name }}</option>
          </select-input>
          <text-input v-model="form.email" :errors="$page.errors.email" class="pr-6 pb-8 w-full lg:w-1/2" label="Email" />
          <text-input v-model="form.phone" :errors="$page.errors.phone" class="pr-6 pb-8 w-full lg:w-1/2" label="Phone" />
          <text-input v-model="form.address" :errors="$page.errors.address" class="pr-6 pb-8 w-full lg:w-1/2" label="Address" />
          <text-input v-model="form.city" :errors="$page.errors.city" class="pr-6 pb-8 w-full lg:w-1/2" label="City" />
          <text-input v-model="form.region" :errors="$page.errors.region" class="pr-6 pb-8 w-full lg:w-1/2" label="Province/State" />
          <select-input v-model="form.country" :errors="$page.errors.country" class="pr-6 pb-8 w-full lg:w-1/2" label="Country">
            <option :value="null" />
            <option value="CA">Canada</option>
            <option value="US">United States</option>
          </select-input>
          <text-input v-model="form.postal_code" :errors="$page.errors.postal_code" class="pr-6 pb-8 w-full lg:w-1/2" label="Postal code" /> -->
        </div>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
          <button v-if="!invoice.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Invoice</button>
          <loading-button :loading="sending" class="btn-indigo ml-auto" type="submit">Update Invoice</loading-button>
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
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  metaInfo() {
    return {
      title: `Invoice ${this.invoice.invoice_id} for ${this.invoice.customer_name}`,
    }
  },
  layout: Layout,
  components: {
    LoadingButton,
    SelectInput,
    TextInput,
    TextareaInput,
    TrashedMessage,
  },
  props: {
    invoice: Object,
    customers: Array,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
        description: this.invoice.description,
        amount: this.invoice.amount,
        customer_id: this.invoice.customer_id,
        currency: this.invoice.currency,
        due_date: this.invoice.due_date,
      },
    }
  },
  methods: {
    submit() {
      this.sending = true
      this.$inertia.put(this.route('invoices.update', this.invoice.id), this.form)
        .then(() => this.sending = false)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this invoice?')) {
        this.$inertia.delete(this.route('invoices.destroy', this.invoice.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this invoice?')) {
        this.$inertia.put(this.route('invoices.restore', this.invoice.id))
      }
    },
  },
}
</script>
