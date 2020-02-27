<template>
  <div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('dashboard')">
        <icon name="dashboard" class="w-4 h-4 mr-2" :class="isUrl('') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Dashboard</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('customers')">
        <icon name="users" class="w-4 h-4 mr-2" :class="isUrl('customers') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('customers') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Customers</div>
      </inertia-link>
    </div>
    <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('invoices')">
        <icon name="printer" class="w-4 h-4 mr-2" :class="isUrl('invoices') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('invoices') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Invoices</div>
      </inertia-link>
    </div>
    <!-- <div class="mb-4">
      <inertia-link class="flex items-center group py-3" :href="route('reports')">
        <icon name="printer" class="w-4 h-4 mr-2" :class="isUrl('reports') ? 'fill-white' : 'fill-indigo-400 group-hover:fill-white'" />
        <div :class="isUrl('reports') ? 'text-white' : 'text-indigo-300 group-hover:text-white'">Reports</div>
      </inertia-link>
    </div> -->
  </div>
</template>

<script>
import Icon from '@/Shared/Icon'

export default {
  components: {
    Icon,
  },
  props: {
    url: String,
  },
  methods: {
    isUrl(...urls) {
      if (urls[0] === '') {
        return this.url === ''
      }

      return urls.filter(url => this.url.startsWith(url)).length
    },
  },
}
</script>
