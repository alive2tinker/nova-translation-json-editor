<template>
    <div>
        <card v-if="loading" class="bg-90 flex flex-col items-center justify-center" style="min-height: 300px">
            <svg class="spin fill-80 mb-6" width="69" height="72" viewBox="0 0 23 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.12 20.455A12.184 12.184 0 0 1 11.5 24a12.18 12.18 0 0 1-9.333-4.319c4.772 3.933 11.88 3.687 16.36-.738a7.571 7.571 0 0 0 0-10.8c-3.018-2.982-7.912-2.982-10.931 0a3.245 3.245 0 0 0 0 4.628 3.342 3.342 0 0 0 4.685 0 1.114 1.114 0 0 1 1.561 0 1.082 1.082 0 0 1 0 1.543 5.57 5.57 0 0 1-7.808 0 5.408 5.408 0 0 1 0-7.714c3.881-3.834 10.174-3.834 14.055 0a9.734 9.734 0 0 1 .03 13.855zM4.472 5.057a7.571 7.571 0 0 0 0 10.8c3.018 2.982 7.912 2.982 10.931 0a3.245 3.245 0 0 0 0-4.628 3.342 3.342 0 0 0-4.685 0 1.114 1.114 0 0 1-1.561 0 1.082 1.082 0 0 1 0-1.543 5.57 5.57 0 0 1 7.808 0 5.408 5.408 0 0 1 0 7.714c-3.881 3.834-10.174 3.834-14.055 0a9.734 9.734 0 0 1-.015-13.87C5.096 1.35 8.138 0 11.5 0c3.75 0 7.105 1.68 9.333 4.319C16.06.386 8.953.632 4.473 5.057z" fill-rule="evenodd" />
            </svg>

            <h1 class="text-white text-4xl text-90 font-light mb-6">
                Loading...
            </h1>
        </card>
        <div class="bg-white p-4" v-else>
            <div class="flex justify-center border-b border-gray-500 pb-5">
                <div class="flex space-x-4">
                    <button @click="currentPage = index" :class="{ 'px-3 py-2 rounded-md': true, 'bg-blue-500 text-white focus:outline-none': isCurrent(index) }" v-for="(locale, index) in locales" :key="index">{{ locale.name }}</button>
                </div>
            </div>
            <div v-for="(locale, index) in locales" :key="index" v-show="isCurrent(index)">
                <table class="w-full table table-auto">
                    <thead>
                        <tr>
                            <th>Key</th>
                            <th>Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="py-4" v-for="(key, value) in locale.strings">
                            <td class="overflow-hidden">{{ value }}</td>
                            <th>
                                <input type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" v-model="locale.strings[value]" />
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="max-w-2xl mx-auto flex justify-center">
                <button class="text-white bg-green-500 rounded-md py-2 px-3 my-3" @click="updateData">update</button>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            loading: true,
            currentPage: 0,
            perPage: 10
        };
    },
    metaInfo() {
        return {
            title: 'NovaTranslationEditor'
        };
    },
    mounted() {
        this.getData();
    },
    methods: {
        getData() {
            Nova.request()
                .get('/nova-vendor/nova-translation-editor/locales')
                .then(response => {
                    this.locales = response.data;
                    this.loading = false;
                });
        },
        updateData() {
            this.loading = true;
            let formData = new FormData();
            formData.append('locales', JSON.stringify(this.locales));
            Nova.request().post('/nova-vendor/nova-translation-editor/updateLocales', formData).then(response => {
                this.loading = false;
            })
        },
        isCurrent(index) {
            return this.currentPage === index;
        }
    }
};
</script>

<style>
/* Scoped Styles */
</style>
