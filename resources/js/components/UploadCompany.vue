<template>
    <div class="row mb-2">
        <div class="col">
            <input type="file" class="form-control" name="company-file" @change="onCheckUpload">
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <button type="button" class="btn btn-primary" @click="uploadFile">{{$t("Upload")}}</button>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{$t("Code")}}</th>
                        <th>{{$t("CompanyName")}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="company in companies" :key="company.code">
                        <td>{{company.code}}</td>
                        <td>{{company.name}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.showData();
        },
        data() {
            return {
                file: '',
                companies: []
            }
        },
        methods: {
            onCheckUpload(e) {
                this.file = e.target.files[0];
            },
            uploadFile() {
                this.companies = [];

                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                };

                const formData = new FormData();
                formData.append("file", this.file);

                axios.post('/company/upload', formData, config)
                .then((res) => {
                    this.$toast.success(this.$t('Uploaded'));

                    this.showData();
                })
                .catch((err) => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            showData() {
                axios.post('/company/gets')
                .then((res) => {
                    res.data.forEach(d => {
                        this.companies.push({
                            code: d['code'],
                            name: d['name']
                        })
                    });
                })
                .catch((err) => {
                    console.log(err);
                    this.$toast.error(err);
                });
            }
        }
    }
</script>

<style>

</style>