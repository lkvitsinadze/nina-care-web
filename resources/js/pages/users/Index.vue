<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Card, CardHeader, CardContent, CardFooter } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
  users: {
    type: Object,
    required: true
  },
  filters: {
    type: Object,
    default: () => ({ search: '' })
  }
});

// Use computed property to safely access nested properties
const userData = computed(() => props.users?.data || []);
const search = ref(props.filters?.search || '');

watch(search, debounce(function (value) {
  router.get(route('users.index'), { search: value }, {
    preserveState: true,
    replace: true
  });
}, 300));

interface Address {
  street: string;
  city: string;
  post_code: string;
  country: string;
}

const truncateAddress = (address: Address | null): string => {
  if (!address) return 'No address';
  
  const fullAddress = `${address.street}, ${address.city}, ${address.post_code}, ${address.country}`;
  return fullAddress.length > 40 ? fullAddress.substring(0, 37) + '...' : fullAddress;
};

const navigateToUserEdit = (userId: number): void => {
  router.get(route('users.edit', userId));
};
</script>

<template>
  <AppLayout>
    <Head title="Users" />

    <div class="container py-6 mx-auto">
      <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Users</h1>
        <Link :href="route('users.create')">
          <Button variant="default">Create User</Button>
        </Link>
      </div>

      <Card>
        <CardHeader>
          <Input
            v-model="search"
            type="search"
            placeholder="Search users..."
            class="w-full"
          />
        </CardHeader>

        <CardContent class="p-0">
          <div class="overflow-x-auto">
            <table class="w-full table-auto">
              <thead>
                <tr class="border-b border-border">
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Name</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Email</th>
                  <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground">Address</th>
                  <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr 
                  v-for="user in userData" 
                  :key="user.id" 
                  @click="navigateToUserEdit(user.id)"
                  class="cursor-pointer hover:bg-muted/50 transition-colors"
                >
                  <td class="p-4 align-middle break-words">{{ user.first_name }} {{ user.last_name }}</td>
                  <td class="p-4 align-middle break-words">{{ user.email }}</td>
                  <td class="p-4 align-middle">
                    <div v-if="user.address" class="truncate max-w-[250px]" :title="user.address.street + ', ' + user.address.city + ', ' + user.address.post_code + ', ' + user.address.country">
                      {{ truncateAddress(user.address) }}
                    </div>
                    <div v-else class="text-muted-foreground">No address</div>
                  </td>
                  <td class="p-4 align-middle text-right" @click.stop>
                    <Button 
                      variant="ghost" 
                      size="sm" 
                      @click="navigateToUserEdit(user.id)"
                    >
                      Edit
                    </Button>
                  </td>
                </tr>
                <tr v-if="userData.length === 0">
                  <td colspan="4" class="p-4 text-center text-muted-foreground">
                    No users found.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </CardContent>

        <CardFooter v-if="props.users && props.users.data.length > 0">
          <div class="flex flex-wrap items-center justify-between w-full">
            <p class="text-sm text-muted-foreground">
              Showing {{ props.users.from }} to {{ props.users.to }} of {{ props.users.total }} results
            </p>
            <div class="flex flex-wrap space-x-1">
              <template v-if="props.users.links">
                <Button
                  v-for="(link, i) in props.users.links"
                  :key="i"
                  variant="outline"
                  size="sm"
                  :disabled="!link.url"
                  :class="{'bg-primary text-primary-foreground': link.active}"
                  @click="link.url && router.get(link.url)"
                >
                  <span v-if="link.label === 'Previous'">Previous</span>
                  <span v-else-if="link.label === 'Next'">Next</span>
                  <span v-else>{{ link.label }}</span>
                </Button>
              </template>
            </div>
          </div>
        </CardFooter>
      </Card>
    </div>
  </AppLayout>
</template>