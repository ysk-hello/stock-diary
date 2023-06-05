<template>
    <h4>{{$t("SearchDiary")}}</h4>
    <div class="row mb-2">
        <div class="col-md-4">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="diary" value="diary" @input="clearDiaries" v-model="diaryType">
                <label class="form-check-label" for="diary">{{$t("WholeDiary")}}</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="companydiary" value="companydiary" @input="clearDiaries" v-model="diaryType">
                <label class="form-check-label" for="companydiary">{{$t("CompanyDiary")}}</label>
            </div>
        </div>
    </div>    
    <div class="row mb-2">
        <div class="col-md-4">
            <input type="text" class="form-control" name="search-text" v-model="keyword"
                 :placeholder="$t('SearchDiaryPlaceholder')" @keyup.enter="searchDiaries(1)" maxlength="20">
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-4">
            <button type="button" class="btn btn-primary btn-width mx-1" @click="searchDiaries(1)">{{$t("Search")}}</button>
            <button type="button" class="btn btn-secondary btn-width mx-1" @click="clearDiaries">{{$t("Clear")}}</button>
        </div>
    </div>

    <div v-if="(diaryType === 'diary') && isSearched">
        <h4 class="mt-5">{{$t("SearchResult")}}</h4>
        <span class="my-3">{{$t("Page")}}: {{ currentPage }} / {{ lastPage }}</span>
        <table class="table table-striped w-auto">
            <thead>
                <tr>
                    <th>{{$t("Date")}}</th>
                    <th>{{$t("Diary")}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="diary in sortDiaries" :key="diary.date">
                    <td><a :href="'/diary?ymd=' + diary.date">{{diary.date}}</a></td>
                    <td>{{diary.text}}</td>
                </tr>
            </tbody>
        </table>
        <!-- container-class: bootstrap class -->
        <paginate
            v-model="currentPage"
            :page-count="lastPage"
            :click-handler="clickCallback"
            :prev-text="'<'"
            :next-text="'>'"
            :container-class="'pagination'"
            :page-class="'page-item'"
        >
        </paginate>
    </div>
    <div v-else-if="(diaryType === 'companydiary') &&  isSearched">
        <h4 class="mt-5">{{$t("SearchResult")}}</h4>
        <span class="my-3">{{$t("Page")}}: {{ currentPage }} / {{ lastPage }}</span>
        <table class="table table-striped w-auto">
            <thead>
                <tr>
                    <th>{{$t("Code")}}</th>
                    <th>{{$t("CompanyName")}}</th>
                    <th>{{$t("Date")}}</th>
                    <th>{{$t("Judgement")}}</th>
                    <th>{{$t("Diary")}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="diary in sortDiaries" :key="diary.date">
                    <td><a :href="'https://kabutan.jp/stock/chart?code=' + diary.code" target="_blank">{{diary.code}}</a></td>
                    <td><a :href="'/companydiary?ymd=' + diary.date + '&code=' + diary.code">{{diary.name}}</a></td>
                    <td>{{diary.date}}</td>
                    <td>
                        <span class="badge" :class="{'bg-danger': diary.judgement==='0', 'bg-secondary': diary.judgement==='1', 'bg-primary': diary.judgement==='2'}">
                            {{ getJudgementStr(diary.judgement) }}
                        </span>
                    </td>
                    <td>{{diary.text}}</td>
                </tr>
            </tbody>
        </table>
        <!-- container-class: bootstrap class -->
        <paginate
            v-model="currentPage"
            :page-count="lastPage"
            :click-handler="clickCallback"
            :prev-text="'<'"
            :next-text="'>'"
            :container-class="'pagination'"
            :page-class="'page-item'"
        >
        </paginate>
    </div>
    <div v-else>
    </div>
</template>

<script>
    export default {
        mounted() {
        },
        data() {
            return {
                keyword: '',
                diaries: [],
                isSearched: false,
                diaryType: 'diary',
                lastPage: 1,
                currentPage: 1
            }
        },
        methods: {
            searchDiaries(page) {
                this.currentPage = page;
                this.isSearched = true;

                if(this.diaryType === 'diary') {
                    axios.get('/search/diary?page=' + page, { 
                    params: {
                        keyword: this.keyword 
                    }
                    })
                    .then(res => {
                        this.lastPage = res.data['last_page'];

                        this.diaries = [];
                        res.data.data.forEach(d => {
                            this.diaries.push({
                                date: d['date'],
                                text: d['text'],
                            });
                        });
                    })
                    .catch(err => {
                        console.log(err);
                        this.$toast.error(err);
                    });
                }
                else if(this.diaryType === 'companydiary') {
                    axios.get('/search/companydiary?page=' + page, { 
                    params: {
                        keyword: this.keyword 
                    }
                    })
                    .then(res => {
                        this.lastPage = res.data['last_page'];

                        this.diaries = [];
                        res.data.data.forEach(d => {
                            this.diaries.push({
                                code: d['code'],
                                name: d['name'],
                                date: d['date'],
                                judgement: d['judgement'],
                                text: d['text'],
                            });
                        });
                    })
                    .catch(err => {
                        console.log(err);
                        this.$toast.error(err);
                    });
                }

            },
            clearDiaries() {
                this.keyword = '';
                this.diaries = [];
                this.isSearched = false;
                this.diaryType = 'diary';
                this.lastPage = 1;
                this.currentPage = 1;
            },
            clickCallback(num) {
                this.searchDiaries(Number(num));
                window.scrollTo({
                    top: 0,
                    left: 0,
                    //behavior: 'smooth'
                });
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
            }
        },
        computed: {
            sortDiaries() {
                this.diaries.sort((a, b) => {
                    a = a['date'];
                    b = b['date'];
                    // 降順
                    return (a === b ? 0 : a < b ? 1 : -1);
                });

                return this.diaries;
            },
        }
    }
</script>

<style>
.btn-width {
    width: 100px;
}
</style>