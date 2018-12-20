<template>
    <card class="flex mb-4 items-center justify-center h-auto">
        <form @submit.prevent="processImport" ref="form">
        <div class="max-w-sm rounded overflow-hidden shadow-lg white-bg ">
          <img
            class="w-full"
            :src="card.image_placeholder"
            alt="Sunset in the mountains"
          >
          <div class="px-6 py-4">
            <div class="font-bold text-xl mb-2">Import {{this.card.resourceLabel}}</div>
                <div class="py-4">
                    <span class="form-file mr-4">
                        <input
                            ref="fileField"
                            class="form-file-input"
                            type="file"
                            :id="inputName"
                            :name="inputName"
                            @change="fileChange"
                            accept=".csv"
                        />
                        <label :for="inputName" class="form-file-btn btn btn-default btn-primary">
                            {{__('Choose File')}}
                        </label>
                    </span>
                    <span class="text-gray-50">
                        {{ currentLabel }}
                    </span>

                </div>

                <div class="flex">
                    <div v-if="errors">
                        <p class="text-danger">{{firstError}}</p>
                    </div>
                    
                </div>
          </div>

          <div class="px-6 py-4">
            <button
                :disabled="working"
                type="submit"
                class="btn btn-sm btn-outline"
            >
                <loader v-if="working" width="30"></loader>
                <span v-else>{{__('Import')}}</span>
            </button>
          </div>
        </div>
        </form>
    </card>
</template>

<script>
export default {
    props: ['card'],

    data() {
        return {
            fileName: '',
            file: null,
            label: 'no file selected',
            working: false,
            errors: null,
        };
    },

    mounted() {
        //
    },

    methods: {
        fileChange(event) {
            let path = event.target.value;
            let fileName = path.match(/[^\\/]*$/)[0];
            this.fileName = fileName;
            this.file = this.$refs.fileField.files[0];
        },
        processImport() {
            if (!this.file) {
                return;
            }
            this.working = true;
            let formData = new FormData();
            formData.append('file', this.file);
            Nova.request()
                .post(
                    '/nova-vendor/ImportData/endpoint/' + this.card.resource,
                    formData
                )
                .then(({ data }) => {
                    this.$toasted.success(data.message);
                    this.$parent.$parent.$parent.$parent.getResources();
                    this.errors = null;
                })
                .catch(({ response }) => {
                    if (response.data.danger) {
                        this.$toasted.error(response.data.danger);
                        this.errors = null;
                    } else {
                        this.errors = response.data.errors;
                    }
                })
                .finally(() => {
                    this.working = false;
                    this.file = null;
                    this.fileName = '';
                    this.$refs.form.reset();
                });
        },
    },
    computed: {
        /**
         * The current label of the file field
         */
        currentLabel() {
            return this.fileName || this.label;
        },

        firstError() {
            return this.errors ? this.errors[Object.keys(this.errors)[0]][0] : null;
        },

        inputName() {
            return 'file-import-input-' + this.card.resource;
        },
    },
};
</script>
