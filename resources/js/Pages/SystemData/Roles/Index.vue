<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
</script>

<template>
    <AppLayout title="recruitment-request">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Recruitment request
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                  <table class="items-center w-full bg-transparent border-collapse">
                    <thead>
                    <tr>
                      <th class="px-2 text-teal-500 align-middle border-b border-solid border-gray-200 py-3 text-sm whitespace-nowrap font-light text-left">
                        name
                      </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, key) in users" :key="key">
                      <td class="border-b border-gray-200 align-middle font-light text-sm whitespace-nowrap px-2 py-4 text-left">
                        {{ item.name }}
                      </td>
                    </tr>
                    </tbody>
                  </table>

                    <Welcome />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
