<template>
    <h4 class="mt-3">{{$t("FavoriteDiary")}}</h4>
    <p class="my-3">{{$t("FavoriteUpperLimitCount")}}{{$t("Current")}}: {{ companies.length }}{{$t("Count")}}</p>
    <div id="favorite-table">
        <table class="table table-striped w-auto">
            <thead>
                <tr>
                    <th class="text-center">{{$t("Favorite")}}</th>
                    <th>{{$t("Code")}}</th>
                    <th>{{$t("CompanyName")}}</th>
                    <th>{{$t("Judgement")}}</th>
                    <th>{{$t("Diary")}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="company in sortCompanies" :key="company.code">
                    <td class="text-center">
                            <i class="fa-solid fa-star fa-lg" v-show="company.isFavorite" @click="toggleFavorite(company.code)"></i>
                            <i class="fa-regular fa-star fa-lg" v-show="!company.isFavorite" @click="toggleFavorite(company.code)"></i>
                    </td>
                    <td><a :href="'https://kabutan.jp/stock/chart?code=' + company.code" target="_blank">{{company.code}}</a></td>
                    <td><a :href="'/companydiary?ymd=' + ymd + '&code=' + company.code">{{company.name}}</a></td>
                    <td>
                        <span class="badge" :class="{'bg-danger': company.judgement==='0', 'bg-secondary': company.judgement==='1', 'bg-primary': company.judgement==='2'}">
                            {{ getJudgementStr(company.judgement) }}
                        </span>
                    </td>
                    <td class="text-line">{{company.text}}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        mounted() {
            this.getFavorites();
        },
        data() {
            return {
                companies: []
            }
        },
        props: ['ymd'],
        methods: {
            getFavorites() {
                axios.get('/favorite/gets',{
                    params: {
                        date: this.ymd
                    }
                })
                .then(res => {
                    this.companies = [];
                    res.data.forEach(d => {
                        this.companies.push({
                            code: d['code'],
                            name: d['name'],
                            judgement: d['judgement'],
                            text: d['text'],
                            isFavorite: true
                        });
                    });
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            toggleFavorite(code) {
                this.companies.forEach(c => {
                    if(c.code === code){
                        axios.get('/favorite/toggle', { 
                            params: {
                                code: c.code
                            }
                        })
                        .then(res =>{
                            if(res.data['state'] === 'upper-limit'){
                                alert(this.$t('FavoriteUpperLimit'));
                                c.isFavorite = false;
                            }
                            else{
                                c.isFavorite = !c.isFavorite;
                                if(res.data['state'] === 'delete'){
                                    // 更新
                                    this.getFavorites();
                                }
                            }
                        })
                        .catch(err => {
                            console.log(err);
                            this.$toast.error(err);
                        });
                    }
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
            },
        },
        computed: {
            sortCompanies() {
                this.companies.sort((a, b) => {
                    a = a['code'];
                    b = b['code'];
                    return (a === b ? 0 : a > b ? 1 : -1);
                });

                return this.companies;
            }
        }
    }
</script>

<style>
.text-line {
    white-space: pre-line;
}
</style>