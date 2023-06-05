<template>
    <div class="row my-3">
        <div class="col">
            <h2><a :href="'https://kabutan.jp/stock/chart?code=' + code" target="_blank">{{ code }}</a>: <span v-text="name"></span></h2>
            <div class="mb-3">
                {{$t("Favorite")}}: 
                <i class="fa-solid fa-star fa-lg" v-show="isFavorite" @click="toggleFavorite()"></i>
                <i class="fa-regular fa-star fa-lg" v-show="!isFavorite" @click="toggleFavorite()"></i>
            </div>
            <h4>{{ ymd }} 
                <span class="badge" :class="{'bg-danger': judgement==='0', 'bg-secondary': judgement==='1', 'bg-primary': judgement==='2'}" v-show="!isEdited">
                    {{ getJudgementStr(judgement) }}
                </span>
            </h4>
        </div>
    </div>
    <div class="row mb-3 d-flex align-items-center">
        <div class="col-md-3">
            <button type="button" class="btn btn-primary btn-width" v-show="!isEdited" @click="editDiary">{{ $t("Edit") }}</button>
            <i class="fa-solid fa-trash fa-lg" v-show="isEdited" @click="clickDelete"></i>
            <modal v-model="isShow" :title="$t('Confirm')" wrapper-class="modal-wrapper">
                <p>{{$t("DeleteConfirm")}}</p>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-danger btn-width mx-3" @click="deleteDiary">{{$t("OK")}}</button>
                    <button type="button" class="btn btn-secondary btn-width mx-3" @click="cancelDelete">{{$t("Cancel")}}</button>
                </div>
            </modal>
        </div>
        <div class="col-md-6"></div>
        <div class="col-md-3">
            <select class="form-select w-100" v-model="judgement" v-show="isEdited">
                <option value=""></option>
                <option value="0">{{$t("Buy")}}</option>
                <option value="1">{{$t("Neutral")}}</option>
                <option value="2">{{$t("Sell")}}</option>
            </select>
        </div>
    </div>

    <div class="row">
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
            <span class="badge" :class="{'bg-danger': diary.judgement==='0', 'bg-secondary': diary.judgement==='1', 'bg-primary': diary.judgement==='2'}">
                {{getJudgementStr(diary.judgement)}}
            </span>
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
            this.getName();
            this.checkFavorite();
            this.getDiary();
            this.getDiaries();
        },
        data() {
            return {
                isFavorite: false,
                name: '',
                diaryText: '',
                textCount: 0,
                diaries: [],
                isEdited: false,
                isShow: false,
                lastPage: 1,
                currentPage: 1,
                judgement: null
            }
        },
        props: ['code', 'ymd'],
        methods: {
            editDiary() {
                this.isEdited = !this.isEdited;
            },
            clickDelete() {
                this.isShow = true;
            },
            deleteDiary() {
                this.diaries =[];
                this.isShow = false;
                axios.get('/companydiary/delete', { 
                    params: {
                        company_code: this.code,
                        date: this.ymd
                    }
                })
                .then(res =>{
                    this.diaryText = '';
                    this.isEdited = false;
                    this.judgement = null;
                    this.currentPage = 1;
                    this.getDiaries();

                    // https://stackoverflow.com/questions/58581304/use-vue-i18n-inside-script
                    // https://takumon.com/vue-i18n-in-script-tag/
                    this.$toast.success(this.$t('Deleted'));
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            getName(){
                axios.get('/company/getname', { 
                    params: {
                        code: this.code,
                    }
                })
                .then(res =>{
                    this.name = res.data['name'];
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            checkFavorite(){
                axios.get('/favorite/check', { 
                    params: {
                        code: this.code,
                    }
                })
                .then(res =>{
                    this.isFavorite = res.data['state'];
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            getDiary() {
                axios.get('/companydiary/get', { 
                    params: {
                        company_code: this.code,
                        date: this.ymd
                    }
                })
                .then(res =>{
                    this.diaryText = res.data['text'];
                    this.textCount = this.diaryText ? this.diaryText.length : 0;
                    this.judgement = res.data['judgement'];
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            getDiaries() {
                axios.get('/companydiary/gets', { 
                    params: {
                        company_code: this.code,
                        date: this.ymd,
                        page: this.currentPage
                    }
                })
                .then(res =>{
                    this.lastPage = res.data['last_page'];

                    res.data.data.forEach(d => {
                        this.diaries.push({
                            date: d['date'],
                            text: d['text'],
                            judgement: d['judgement']
                        });
                    });
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            saveDiary() {
                this.diaries =[];
                this.isShow = false;
                axios.post('/companydiary/save', { company_code: this.code, date: this.ymd, text: this.diaryText, judgement: this.judgement })
                .then(res =>{
                    this.isEdited = false;
                    this.currentPage = 1;
                    this.getDiaries();

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
            getJudgementStr(judge){
                switch(judge){
                    case '0':
                        return this.$t('Buy');
                    case '1':
                        return this.$t('Neutral');
                    case '2':
                        return this.$t('Sell');
                    default:
                        return '';
                }
            },
            toggleFavorite() {
                axios.get('/favorite/toggle', { 
                    params: {
                        code: this.code,
                    }
                })
                .then(res =>{
                    if(res.data['state'] === 'upper-limit'){
                        alert(this.$t('FavoriteUpperLimit'));
                        this.isFavorite = false;
                    }
                    else{
                        this.isFavorite = !this.isFavorite;
                    }
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
        },
        computed: {
            sortDiaries() {
                this.diaries.sort((a, b) => {
                    a = a['date'];
                    b = b['date'];
                    return (a === b ? 0 : a > b ? -1 : 1);
                });

                return this.diaries;
            },
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