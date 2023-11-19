<script setup lang="ts">
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import { Head, useForm, router } from "@inertiajs/vue3";


const form = useForm({
    name: "",
    email: "",
    logo: null, 
    website: "",
});

const submit = () => {
    form.post(route("companies.store"));
};

const handleFileChange = (event: any) => {
    const file = event.target.files[0];
    form.logo = file;
};
const goBack = () => {
    router.visit(route("companies.index"));
};
</script>

<template>
    <Head title="Company Create" />

    <AuthenticatedLayout>
        <template #header>
            <div class="inline-flex gap-4 flex-row-reverse items-center">
                    <h2
                        class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
                    >
                        Create Company
                    </h2>
                    <a-button class="text-sm dark:text-white " @click="goBack">Back</a-button>

                </div>
            </template>

        <div class="py-12">
            <div class="mx-auto max-w-xl">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <form @submit.prevent="submit">
                            <div>
                                <InputLabel for="name" value="Name" />

                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    autocomplete="name"
                                />

                                <InputError
                                    class="mt-2"
                                    :message="form.errors.name"
                                />
                            </div>

                            <div class="my-6">
                                <label
                                    for="content"
                                    class="block mb-2 text-sm font-medium text-gray-900"
                                    >Email</label
                                >
                                <TextInput
                                    type="email"
                                    v-model="form.email"
                                    name="email"
                                    id=""
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                />

                                <div
                                    v-if="form.errors.email"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.email }}
                                </div>
                            </div>
                            <div class="my-6">
                                <label
                                    for="content"
                                    class="block mb-2 text-sm font-medium text-gray-900"
                                    >Website</label
                                >
                                <TextInput
                                    type="text"
                                    v-model="form.website"
                                    name="website"
                                    id=""
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                />

                                <div
                                    v-if="form.errors.website"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.email }}
                                </div>
                            </div>

                            <div class="my-6">
                                <label
                                    for="logo"
                                    class="block mb-2 text-sm font-medium text-gray-900"
                                    >Logo</label
                                >
                                <input
                                    type="file"
                                    name="logo"
                                    accept="image/*"
                                    v-on:change="handleFileChange"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                />
                                <div
                                    v-if="form.errors.logo"
                                    class="text-sm text-red-600"
                                >
                                    {{ form.errors.logo }}
                                </div>
                            </div>
                          <div class="inline-flex gap-4">
                            <PrimaryButton
                                type="submit"
                                :class="{ 'opacity-25 mt-5 pt-5 ': form.processing }"
                                :disabled="form.processing"
                            >
                                Submit
                            </PrimaryButton>
                            <SecondaryButton
                                type="button"
                                :class="{ 'opacity-25 mt-5 pt-5 bg-gray-500': form.processing }"
                                :disabled="form.processing"
                                @click="goBack"
                            >
                                Cancel
                            </SecondaryButton>
                          </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
