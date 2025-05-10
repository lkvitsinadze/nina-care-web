<script setup lang="ts">
import { reactive } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
  first_name: '',
  last_name: '',
  email: '',
  password: '',
  address: {
    country: '',
    city: '',
    post_code: '',
    street: ''
  }
});

function submit() {
  form.post(route('users.store'));
}
</script>

<template>
  <div>
    <Head title="Create User" />

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
          <h1 class="text-2xl font-semibold text-gray-900">Create User</h1>
          <Link 
            :href="route('users.index')" 
            class="px-4 py-2 border border-gray-300 bg-white text-gray-700 rounded hover:bg-gray-50 transition"
          >
            Back to Users
          </Link>
        </div>

        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
          <form @submit.prevent="submit" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- User Information -->
              <div class="md:col-span-2">
                <h2 class="text-lg font-medium text-gray-900 mb-4">User Information</h2>
              </div>
              
              <div>
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input
                  id="first_name"
                  v-model="form.first_name"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                  required
                />
                <div v-if="form.errors.first_name" class="text-red-500 text-sm mt-1">{{ form.errors.first_name }}</div>
              </div>
              
              <div>
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input
                  id="last_name"
                  v-model="form.last_name"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                  required
                />
                <div v-if="form.errors.last_name" class="text-red-500 text-sm mt-1">{{ form.errors.last_name }}</div>
              </div>
              
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                  id="email"
                  v-model="form.email"
                  type="email"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                  required
                />
                <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</div>
              </div>

              <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input
                  id="password"
                  v-model="form.password"
                  type="password"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                  required
                />
                <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</div>
              </div>
              
              <!-- Address Information -->
              <div class="md:col-span-2 mt-4">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Address Information</h2>
              </div>
              
              <div>
                <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
                <input
                  id="country"
                  v-model="form.address.country"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                  required
                />
                <div v-if="form.errors['address.country']" class="text-red-500 text-sm mt-1">{{ form.errors['address.country'] }}</div>
              </div>

              <div>
                <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                <input
                  id="city"
                  v-model="form.address.city"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                  required
                />
                <div v-if="form.errors['address.city']" class="text-red-500 text-sm mt-1">{{ form.errors['address.city'] }}</div>
              </div>

              <div>
                <label for="post_code" class="block text-sm font-medium text-gray-700">Post Code</label>
                <input
                  id="post_code"
                  v-model="form.address.post_code"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                  required
                />
                <div v-if="form.errors['address.post_code']" class="text-red-500 text-sm mt-1">{{ form.errors['address.post_code'] }}</div>
              </div>

              <div>
                <label for="street" class="block text-sm font-medium text-gray-700">Street</label>
                <input
                  id="street"
                  v-model="form.address.street"
                  type="text"
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                  required
                />
                <div v-if="form.errors['address.street']" class="text-red-500 text-sm mt-1">{{ form.errors['address.street'] }}</div>
              </div>
            </div>
            
            <div class="mt-6 text-right">
              <button
                type="submit"
                class="py-2 px-4 bg-blue-600 text-white rounded hover:bg-blue-700 transition"
                :disabled="form.processing"
              >
                Create User
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>