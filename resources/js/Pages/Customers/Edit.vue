<template>
  <div>
    <h1 class="mb-8 font-bold text-3xl">
      <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="route('customers')">Customers</inertia-link>
      <span class="text-indigo-400 font-medium">/</span>
      {{ form.first_name }} {{ form.last_name }}
    </h1>
    <trashed-message v-if="customer.deleted_at" class="mb-6" @restore="restore">
      This customer has been deleted.
    </trashed-message>
    <div class="bg-white rounded shadow overflow-hidden max-w-3xl">
      <form @submit.prevent="submit">
        <div class="p-8 -mr-6 -mb-8 flex flex-wrap">
          <text-input v-model="form.first_name" :errors="$page.errors.first_name" class="pr-6 pb-8 w-full lg:w-1/2" label="First name" />
          <text-input v-model="form.last_name" :errors="$page.errors.last_name" class="pr-6 pb-8 w-full lg:w-1/2" label="Last name" />
          <text-input v-model="form.email" :errors="$page.errors.email" class="pr-6 pb-8 w-full lg:w-1/2" label="Email" readonly="true" />
          <text-input v-model="form.phone" :errors="$page.errors.phone" class="pr-6 pb-8 w-full lg:w-1/2" label="Phone" />
          <text-input v-model="form.address" :errors="$page.errors.address" class="pr-6 pb-8 w-full lg:w-1/2" label="Address" />
          <text-input v-model="form.city" :errors="$page.errors.city" class="pr-6 pb-8 w-full lg:w-1/2" label="City" />
          <text-input v-model="form.region" :errors="$page.errors.region" class="pr-6 pb-8 w-full lg:w-1/2" label="Province/State" />
          <select-input v-model="form.country" :errors="$page.errors.country" class="pr-6 pb-8 w-full lg:w-1/2" label="Country">
            <option :value="null" />
            <option value="NG">Nigeria</option>
          </select-input>
          <text-input v-model="form.postal_code" :errors="$page.errors.postal_code" class="pr-6 pb-8 w-full lg:w-1/2" label="Postal code" />
        </div>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
          <button v-if="!customer.deleted_at" class="text-red-600 hover:underline" tabindex="-1" type="button" @click="destroy">Delete Customer</button>
          <loading-button :loading="sending" class="btn-indigo ml-auto" type="submit">Update Customer</loading-button>
        </div>
      </form>
    </div>
    <h2 class="mt-12 font-bold text-2xl">Invoices</h2>
    <div class="mt-6 bg-white rounded shadow overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <tr class="text-left font-bold">
            <th class="px-6 pt-6 pb-4">Id</th>
            <th class="px-6 pt-6 pb-4">Amount</th>
            <th class="px-6 pt-6 pb-4">Status</th>
            <th class="px-6 pt-6 pb-4">Due Date</th>
            <th class="px-6 pt-6 pb-4" colspan="2">Paid Date</th>
        </tr>
        <tr v-for="invoice in customer.invoices" :key="invoice.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center focus:text-indigo-500" :href="route('invoices.edit', invoice.id)">
              {{ invoice.paystack_invoice_id }}
              <icon v-if="invoice.deleted_at" name="trash" class="flex-shrink-0 w-3 h-3 fill-gray-400 ml-2" />
            </inertia-link>
          </td>
          <td class="border-t">
            <inertia-link class="px-6 py-4 flex items-center" :href="route('invoices.edit', invoice.id)" tabindex="-1">
              {{ invoice.amount }}
            </inertia-link>
          </td>
            <td class="border-t">
                <inertia-link class="px-6 py-4 flex items-center" :href="route('invoices.edit', invoice.id)"
                              tabindex="-1">
                    {{ invoice.status }}
                </inertia-link>
            </td>
            <td class="border-t">
                <inertia-link class="px-6 py-4 flex items-center" :href="route('invoices.edit', invoice.id)"
                              tabindex="-1">
                    {{ invoice.due_date }}
                </inertia-link>
            </td>
            <td class="border-t">
                <inertia-link class="px-6 py-4 flex items-center" :href="route('invoices.edit', invoice.id)"
                              tabindex="-1">
                    {{ invoice.paid_at }}
                </inertia-link>
            </td>
          <td class="border-t w-px">
            <inertia-link class="px-4 flex items-center" :href="route('invoices.edit', invoice.id)" tabindex="-1">
              <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
            </inertia-link>
          </td>
        </tr>
        <tr v-if="customer.invoices.length === 0">
          <td class="border-t px-6 py-4" colspan="4">No invoices found.</td>
        </tr>
      </table>
    </div>
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'
import Layout from '@/Shared/Layout'
import LoadingButton from '@/Shared/LoadingButton'
import SelectInput from '@/Shared/SelectInput'
import TextInput from '@/Shared/TextInput'
import TrashedMessage from '@/Shared/TrashedMessage'

export default {
  metaInfo() {
    return { title: this.form.name }
  },
  layout: Layout,
  components: {
    Icon,
    LoadingButton,
    SelectInput,
    TextInput,
    TrashedMessage,
  },
  props: {
    customer: Object,
  },
  remember: 'form',
  data() {
    return {
      sending: false,
      form: {
        first_name: this.customer.first_name,
        last_name: this.customer.last_name,
        email: this.customer.email,
        phone: this.customer.phone,
        address: this.customer.address,
        city: this.customer.city,
        region: this.customer.region,
        country: this.customer.country,
        postal_code: this.customer.postal_code,
      },
    }
  },
  methods: {
    submit() {
      this.sending = true
      this.$inertia.put(this.route('customers.update', this.customer.id), this.form)
        .then(() => this.sending = false)
    },
    destroy() {
      if (confirm('Are you sure you want to delete this customer?')) {
        this.$inertia.delete(this.route('customers.destroy', this.customer.id))
      }
    },
    restore() {
      if (confirm('Are you sure you want to restore this customer?')) {
        this.$inertia.put(this.route('customers.restore', this.customer.id))
      }
    },
  },
}
</script>
