<script setup lang="ts">
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';

const props = defineProps({
  users: Object,
  filters: Object
});

const search = ref(props.filters.search);

watch(search, debounce(function (value) {
  router.get(route('users.index'), { search: value }, {
    preserveState: true,
    replace: true
  });
}, 300));
</script>

<template>
  <div class="bg-gray-800 min-h-screen">
    <Head title="Users" />

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
          <h1 class="text-2xl font-semibold text-white">Users</h1>
          <Link 
            :href="route('users.create')" 
            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-400 transition"
          >
            Create User
          </Link>
        </div>

        <div class="mt-6 bg-gray-700 shadow overflow-hidden sm:rounded-lg">
          <div class="p-4 border-b border-gray-600">
            <div class="flex items-center">
              <input
                v-model="search"
                type="text"
                placeholder="Search users..."
                class="w-full px-4 py-2 border rounded-lg bg-gray-600 text-white placeholder-gray-400 border-gray-500"
              />
            </div>
          </div>
          
          <table class="min-w-full divide-y divide-gray-600">
            <thead>
              <tr>
                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 bg-gray-800 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Address</th>
                <th class="px-6 py-3 bg-gray-800 text-right text-xs font-medium text-gray-300 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-gray-700 divide-y divide-gray-600">
              <tr v-for="user in users.data" :key="user.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-white">{{ user.first_name }} {{ user.last_name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-300">{{ user.email }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div v-if="user.address" class="text-sm text-gray-300">
                    {{ user.address.street }}, {{ user.address.city }}, {{ user.address.post_code }}, {{ user.address.country }}
                  </div>
                  <div v-else class="text-sm text-gray-400">No address</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <Link
                    :href="route('users.edit', user.id)"
                    class="text-blue-400 hover:text-blue-300 mr-3"
                  >
                    Edit
                  </Link>
                </td>
              </tr>
              <tr v-if="users.data.length === 0">
                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-300">
                  No users found.
                </td>
              </tr>
            </tbody>
          </table>
          
          <div v-if="users.data.length > 0" class="px-4 py-3 bg-gray-700 border-t border-gray-600 sm:px-6">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-300">
                Showing {{ users.from }} to {{ users.to }} of {{ users.total }} results
              </div>
              <div class="flex-1 flex justify-end">
                <Link
                  v-for="(link, i) in users.links"
                  :key="i"
                  :href="link.url"
                  v-html="link.label"
                  class="px-3 py-1 border mx-1 rounded border-gray-600 text-gray-300"
                  :class="{'bg-blue-900 text-white': link.active}"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>