<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Card, CardHeader, CardContent } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';

interface FormErrors {
  first_name?: string;
  last_name?: string;
  email?: string;
  'address.country'?: string;
  'address.city'?: string;
  'address.post_code'?: string;
  'address.street'?: string;
}

const props = defineProps({
  user: Object
});

const form = useForm({
  first_name: props.user.first_name,
  last_name: props.user.last_name,
  email: props.user.email,
  address: {
    country: props.user.address?.country || '',
    city: props.user.address?.city || '',
    post_code: props.user.address?.post_code || '',
    street: props.user.address?.street || ''
  },
  errors: {} as FormErrors
});

function submit() {
  form.put(route('users.update', props.user.id));
}
</script>

<template>
  <AppLayout>
    <Head title="Edit User" />

    <div class="container mx-auto px-4 py-6">
      <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold">Edit User</h1>
        <Link :href="route('users.index')">
          <Button variant="outline">Back to Users</Button>
        </Link>
      </div>

      <Card>
        <CardHeader>
          <h2 class="text-lg font-medium">User Information</h2>
        </CardHeader>
        <CardContent>
          <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="grid gap-2">
              <Label for="first_name">First Name</Label>
              <Input id="first_name" v-model="form.first_name" required placeholder="First Name" />
              <InputError :message="form.errors.first_name" />
            </div>

            <div class="grid gap-2">
              <Label for="last_name">Last Name</Label>
              <Input id="last_name" v-model="form.last_name" required placeholder="Last Name" />
              <InputError :message="form.errors.last_name" />
            </div>

            <div class="grid gap-2">
              <Label for="email">Email</Label>
              <Input id="email" v-model="form.email" type="email" required placeholder="Email" />
              <InputError :message="form.errors.email" />
            </div>

            <h2 class="text-lg font-medium md:col-span-2">Address Information</h2>

            <div class="grid gap-2">
              <Label for="country">Country</Label>
              <Input id="country" v-model="form.address.country" required placeholder="Country" />
              <InputError :message="form.errors['address.country']" />
            </div>

            <div class="grid gap-2">
              <Label for="city">City</Label>
              <Input id="city" v-model="form.address.city" required placeholder="City" />
              <InputError :message="form.errors['address.city']" />
            </div>

            <div class="grid gap-2">
              <Label for="post_code">Post Code</Label>
              <Input id="post_code" v-model="form.address.post_code" required placeholder="Post Code" />
              <InputError :message="form.errors['address.post_code']" />
            </div>

            <div class="grid gap-2">
              <Label for="street">Street</Label>
              <Input id="street" v-model="form.address.street" required placeholder="Street" />
              <InputError :message="form.errors['address.street']" />
            </div>

            <div class="md:col-span-2 text-right">
              <Button type="submit" :disabled="form.processing">Update User</Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>