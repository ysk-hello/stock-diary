<template>
    <div class="row mb-2">
        <div class="col-md-7">
            <h2 class="text-center">{{$t("Contact")}}</h2>
        </div>
    </div>
    <div class="row mb-2">
        <label for="name" class="col-form-label col-md-2">
            {{$t("Name")}}
            <span class="badge text-bg-info">
                {{$t("Required")}}
            </span>
        </label>
        <div class="col-md-5">
            <input type="text" name="name" id="name" class="form-control" v-model="name" :disabled="isDisabled">
            <errors :errors="errors.name"></errors>
        </div>
    </div>
    <div class="row mb-2">
        <label for="email" class="col-form-label col-md-2">
            {{$t("Email")}}
            <span class="badge text-bg-info">
                {{$t("Required")}}
            </span>
        </label>
        <div class="col-md-5">
            <input type="email" name="email" id="email" class="form-control" v-model="email" :disabled="isDisabled">
            <errors :errors="errors.email"></errors>
        </div>
    </div>
    <div class="row mb-2">
        <label for="title" class="col-form-label col-md-2">
            {{$t("Title")}}
            <span class="badge text-bg-info">
                {{$t("Required")}}
            </span>
        </label>
        <div class="col-md-5">
            <input type="text" name="title" id="title" class="form-control" v-model="title" :disabled="isDisabled">
            <errors :errors="errors.title"></errors>
        </div>
    </div>
    <div class="row mb-2">
        <label for="message" class="col-form-label col-md-2">
            {{$t("Message")}}
            <span class="badge text-bg-info">
                {{$t("Required")}}
            </span>
        </label>
        <div class="col-md-5">
            <textarea name="message" id="message" cols="30" rows="10" class="form-control" v-model="message" :disabled="isDisabled"></textarea>
            <errors :errors="errors.message"></errors>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-2"></div>
        <div class="col-md-5 d-flex justify-content-end">
            <button class="btn btn-primary" @click="sendMail" :disabled="isDisabled">{{$t("Send")}}</button>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
        },
        data() {
            return {
                name: '',
                email: '',
                title: '',
                message: '',
                errors: {
                    name: [],
                    email: [],
                    title: [],
                    message: []
                },
                isDisabled: false
            }
        },
        methods: {
            validateOK() {
                let errors = {
                    name: [],
                    email: [],
                    title: [],
                    message: []
                };

                if(!this.name) {
                    errors.name.push(this.$t('ErrorInputRequired'));
                } else if(this.name.length > 20) {
                    errors.name.push(this.$t('ErrorInputCharacterCount', { count: 20}));
                }

                let pattern = /^[A-Za-z0-9]{1}[A-Za-z0-9_.-]*@{1}[A-Za-z0-9_.-]+.[A-Za-z0-9]+$/;
                if(!this.email) {
                    errors.email.push(this.$t('ErrorInputRequired'));
                } else if(!this.email.match(pattern)) {
                    errors.email.push(this.$t('ErrorInputMailAddress'));
                }

                if(!this.title) {
                    errors.title.push(this.$t('ErrorInputRequired'));
                } else if(this.title.length > 20) {
                    errors.title.push(this.$t('ErrorInputCharacterCount', { count: 30}));
                }

                if(!this.message) {
                    errors.message.push(this.$t('ErrorInputRequired'));
                } else if(this.message.length > 20) {
                    errors.message.push(this.$t('ErrorInputCharacterCount', { count: 200}));
                }

                this.errors = errors;

                let error = (this.errors.name.length > 0) || (this.errors.email.length > 0)
                     || (this.errors.title.length > 0) || (this.errors.message.length > 0);

                return !error;
            },
            async sendMail() {
                if(!this.validateOK()){
                    this.$toast.error(this.$t('ErrorInput'));
                }
                else{
                    this.isDisabled = true;
                    await axios.post('/contact/mail', { 
                        name: this.name,
                        email: this.email,
                        title: this.title,
                        message: this.message
                     })
                    .then(res =>{
                        this.$toast.success(this.$t('Sent'));
                        this.name = '';
                        this.email = '';
                        this.title = '';
                        this.message = '';
                    })
                    .catch(err => {
                        console.log(err);
                        this.$toast.error(err);
                    });

                    this.isDisabled = false;
                }
            }
        }
    }
</script>

<style>

</style>