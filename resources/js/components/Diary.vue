<template>
    <div class="row my-3">
        <div class="col">
            <h2>{{ ymd }}</h2>
            <button type="button" class="btn btn-primary btn-width" v-show="!isEdited" @click="editDiary">{{$t("Edit")}}</button>
            <i class="fa-solid fa-trash fa-lg" v-show="isEdited" @click="clickDelete"></i>
            <modal v-model="isShow" :title="$t('Confirm')" wrapper-class="modal-wrapper">
                <p>{{$t("DeleteConfirm")}}</p>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-danger btn-width mx-3" @click="deleteDiary">{{$t("OK")}}</button>
                    <button type="button" class="btn btn-secondary btn-width mx-3" @click="cancelDelete">{{$t("Cancel")}}</button>
                </div>
            </modal>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col">
            <!-- テキストエリア -->
            <textarea :class="{'form-control': true, 'not-edit': !isEdited}" name="diary" cols="30" rows="10" v-model="diaryText" :readonly="!isEdited" maxlength="500" @input="inputText"></textarea>
            <div v-show="isEdited">
                <div class="d-flex justify-content-end">
                    {{ textCount }} / 500
                </div>
            </div>
        </div>
    </div>
    <div class="row my-3">
        <div class="col d-flex justify-content-end">
            <button type="button" class="btn btn-primary btn-width mx-3" v-show="isEdited" @click="saveDiary">{{$t("Save")}}</button>
            <button type="button" class="btn btn-secondary btn-width mx-3" v-show="isEdited" @click="cancelSave">{{$t("Cancel")}}</button>
        </div>
    </div>

    <div class="card my-2" v-for="diary in sortDiaries" :key="diary.date">
        <div class="card-header">
            {{diary.date}}
        </div>
        <div class="card-body">
            <div class="card-text text-line">
                {{diary.text}}
            </div>
        </div>
    </div>

    <div class="text-center">
        <i class="fa-solid fa-rotate my-3" v-show="currentPage < lastPage" @click="loadMore">{{$t("MoreSee")}}</i>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getDiary();
            this.getDiaries();
        },
        data() {
            return {
                diaryText: '',
                textCount: 0,
                isEdited: false,
                isShow: false,
                lastPage: 1,
                currentPage: 1,
                diaries: [],
                showModal: false
            }
        },
        props: ['ymd'],
        methods: {
            editDiary() {
                this.isEdited = !this.isEdited;
            },
            clickDelete() {
                this.isShow = true;
            },
            deleteDiary() {
                this.isShow = false;
                axios.get('/diary/delete', { 
                    params: {
                        date: this.ymd
                    }
                })
                .then(res =>{
                    this.diaryText = '';
                    this.isEdited = false;

                    this.$toast.success(this.$t('Deleted'));
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            getDiary() {
                axios.get('/diary/get', { 
                    params: {
                        date: this.ymd
                    }
                })
                .then(res =>{
                    this.diaryText = res.data['text'];
                    this.textCount = this.diaryText ? this.diaryText.length : 0;
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            saveDiary() {
                this.isShow = false;
                axios.post('/diary/save', { date: this.ymd, text: this.diaryText })
                .then(res =>{
                    this.isEdited = false;

                    this.$toast.success(this.$t('Saved'));
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            cancelSave() {
                this.isShow = false;
                this.isEdited = false;
                this.getDiary();
            },
            cancelDelete() {
                this.isShow = false;
            },
            inputText(){
                this.textCount = this.diaryText ? this.diaryText.length : 0;

                // if(this.textCount > 500){
                //     this.$toast.error(this.$t('CharacterUpperLimit', {count: 500}));
                // }
            },
            loadMore() {
                ++this.currentPage;
                this.getDiaries();
            },
            getDiaries() {
                axios.get('/diary/gets', { 
                    params: {
                        date: this.ymd,
                        page: this.currentPage
                    }
                })
                .then(res =>{
                    this.lastPage = res.data['last_page'];

                    res.data.data.forEach(d => {
                        this.diaries.push({
                            date: d['date'],
                            text: d['text']
                        });
                    });
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            }
        },
        computed: {
            sortDiaries() {
                this.diaries.sort((a, b) => {
                    a = a['date'];
                    b = b['date'];
                    return (a === b ? 0 : a > b ? -1 : 1);
                });

                return this.diaries;
            }
        }
    }
</script>

<style>
.modal-wrapper {
    display: flex;
    align-items: center;
}
.not-edit {
    cursor: not-allowed !important;
}
.btn-width {
    width: 100px;
}
.text-line {
    white-space: pre-line;
}
</style>