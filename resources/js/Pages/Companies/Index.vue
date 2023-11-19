<template>
    <div>
        <Head title="Company" />
        <AuthenticatedLayout>
            <template #header>
                <h2
                    class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
                >
                    Company
                </h2>
            </template>

            <div class="xl:p-12 pt-10 xl:mx-10 overflow-scroll">
                <div class="pl-5">
                    <a-button
                        class="editable-add-btn dark:text-white pb-5font-bold border"
                        @click="addCompany"
                    >
                        Add Company
                    </a-button>
                </div>
                <a-table
                    class="dark:bg-slate-300 dark:text-white p-5 rounded-lg overflow-hidden"
                    :dataSource="companies.data"
                    :columns="columns"
                    :pagination="paginationConfig"
                    :filterSearch="true"
                >
                    <template #bodyCell="{ column, record, index }">
                        <template v-if="column.key === 'website'">
                            <a
                                @click.prevent="
                                    redirectToWebsite(record.website)
                                "
                                href="#"
                                class="underline"
                            >
                                {{ record.website }}
                            </a>
                        </template>
                        <template v-else-if="column.key === 'logo'">
                            <img
                                :src="record.logo"
                                alt="Company Logo"
                                class="h-8 w-8 rounded-full"
                            />
                        </template>
                        <template v-else-if="column.key === 'index'">
                            {{
                                (meta.current_page - 1) * meta.per_page +
                                index +
                                1
                            }}
                        </template>
                        <template v-else-if="column.key === 'action'">
                            <span>
                                <a
                                    @click.prevent="editCompany(record)"
                                    class="mr-2 text-blue-600 hover:text-blue-900 hover:underline"
                                    >Edit</a
                                >
                                <a
                                    class="text-red-600 hover:text-red-900 hover:underline"
                                    @click.prevent="deleteCompany(record.id)"
                                    >Delete</a
                                >
                            </span>
                        </template>
                    </template>
                </a-table>
            </div>
            <contextHolder />
            <a-modal
                v-model:open="openModal"
                :title="modalTitle"
                @ok="handleSubmit"
            >
                <template #footer>
                    <a-button key="back" @click="handleCancel">Cancel</a-button>
                    <a-button
                        class="bg-blue-800 text-white font-bold"
                        key="submit"
                        type="primary"
                        :loading="false"
                        @click="handleSubmit"
                        >Submit</a-button
                    >
                </template>
                <div class="mb-10">
                    <form @submit.prevent="handleSubmit">
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
                    </form>
                </div>
            </a-modal>
            <div></div>
        </AuthenticatedLayout>
    </div>
</template>

<script setup lang="ts">
import { defineProps, ref, h } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm, usePage } from "@inertiajs/vue3";
import { Modal } from "ant-design-vue";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";

import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
const openModal = ref<boolean>(false);
const modalTitle = ref<string>("");

const { companies, meta } = defineProps(["companies", "meta"]);
const [modal, contextHolder] = Modal.useModal();
interface Company {
    id: any | null;
    name: string;
    email: string;
    logo: any | null;
    website: string;
}
const columns = ref([
    { title: "#", key: "index" },
    { title: "ID", dataIndex: "id", key: "id" },
    { title: "Logo", dataIndex: "logo", key: "logo" },
    { title: "Name", dataIndex: "name", key: "name" },
    { title: "Email", dataIndex: "email", key: "email" },
    { title: "Website", dataIndex: "website", key: "website" },
    {
        title: "Action",
        key: "action",
    },
]);

const paginationConfig = {
    current: meta.current_page,
    pageSize: meta.per_page,
    total: meta.total,
    showSizeChanger: true,
    pageSizeOptions: ["10", "20", "50", "100"],
    onChange: (page: number, pageSize: number) => {
        fetchDataForPage(page, pageSize);
    },
};

const fetchDataForPage = async (page: number, pageSize: number) => {
    router.visit(route("companies.index", { page }), {
        only: ["companies", "meta"],
        preserveScroll: true,
    });
};

const addCompany = () => {

    router.visit(route("companies.create"));

    form.clearErrors();
    form.reset();
    openModal.value = true;
    modalTitle.value = "Add Company";
};

const handleSubmit = () => {
    if (form.id) {
        router.post(
            route("companies.update", form.id),
            {
                preserveScroll: true,
                _method: "put",
                name: form.name,
                email: form.email,
                website: form.website,
                logo: form.logo,
            },
            {
                onSuccess: () => {
                    form.reset();
                    router.reload();
                    openModal.value = false;
                },
                onError: () => {
                    alert("Something went wrong");
                },
            }
        );
    } else {
        form.post(route("companies.store"), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                router.reload();
                openModal.value = false;
            },
        });
    }
};
const handleCancel = () => {
    openModal.value = false;
};

const redirectToWebsite = (website: string) => {
    window.open(website, "_blank");
};
const editCompany = (company: Company) => {

    router.visit(route("companies.edit", { id: company.id }));

    modalTitle.value = "Edit Company";
    form.name = company.name;
    form.email = company.email;
    form.website = company.website;
    form.logo = company.logo;
    form.id = company.id;
    openModal.value = true;
};

const deleteCompany = (id: number) => {
    modal.confirm({
        title: "Are you sure you want to delete this company?",
        icon: h(ExclamationCircleOutlined),
        content: "This action cannot be undone.",
        okText: "Yes",
        okType: "danger",
        cancelText: "No",
        onOk() {
            router.delete(
                route("companies.destroy", { id }),

                {
                    preserveScroll: true,
                    onSuccess: () => {
                        router.reload();
                    },
                }
            );
        },
        onCancel() {},
    });
};
const form = useForm({
    id: null,
    name: "",
    email: "",
    logo: null,
    website: "",
});

const handleFileChange = (event: any) => {
    const file = event.target.files[0];
    form.logo = file;
};
</script>
