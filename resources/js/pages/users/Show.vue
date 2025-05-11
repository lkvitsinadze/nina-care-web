<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <AppLayout>
    <Head :title="`User: ${props.user.first_name} ${props.user.last_name}`" />

    <div class="container mx-auto px-4 py-6">
      <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">User Details</h1>
        <Link :href="route('users.index')">
          <Button variant="outline">Back to Users</Button>
        </Link>
      </div>

      <Card>
        <CardHeader>
          <h2 class="text-lg font-medium">Personal Information</h2>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="font-medium">First Name:</p>
              <p>{{ props.user.first_name }}</p>
            </div>
            <div>
              <p class="font-medium">Last Name:</p>
              <p>{{ props.user.last_name }}</p>
            </div>
            <div>
              <p class="font-medium">Email:</p>
              <p>{{ props.user.email }}</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card class="mt-6">
        <CardHeader>
          <h2 class="text-lg font-medium">Address Information</h2>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="font-medium">Country:</p>
              <p>{{ props.user.address?.country || 'N/A' }}</p>
            </div>
            <div>
              <p class="font-medium">City:</p>
              <p>{{ props.user.address?.city || 'N/A' }}</p>
            </div>
            <div>
              <p class="font-medium">Post Code:</p>
              <p>{{ props.user.address?.post_code || 'N/A' }}</p>
            </div>
            <div>
              <p class="font-medium">Street:</p>
              <p>{{ props.user.address?.street || 'N/A' }}</p>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>