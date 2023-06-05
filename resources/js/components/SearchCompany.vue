<template>
    <div class="row mb-2">
        <h4>{{$t("SearchCompany")}}</h4>
        <div class="col-md-4">
            <input type="text" class="form-control" name="search-text" v-model="keyword"
                 :placeholder="$t('SearchCompanyPlaceholder')" @keyup.enter="searchCompanies(1)" maxlength="20">
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-4">
            <button type="button" class="btn btn-primary btn-width mx-1" @click="searchCompanies(1)">{{$t("Search")}}</button>
            <button type="button" class="btn btn-secondary btn-width mx-1" @click="clearCompanies">{{$t("Clear")}}</button>
        </div>
    </div>

    <div v-if="isSearched">
        <h4 class="mt-5">{{$t("SearchResult")}}</h4>
        <span class="my-3">{{$t("Page")}}: {{ currentPage }} / {{ lastPage }}</span>
        <p class="my-3">{{$t("FavoriteUpperLimitCount")}}</p>
        <table class="table table-striped w-auto">
            <thead>
                <tr>
                    <th class="text-center">{{$t("Favorite")}}</th>
                    <th>{{$t("Code")}}</th>
                    <th>{{$t("CompanyName")}}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="company in sortCompanies" :key="company.code">
                    <td class="text-center">
                        <i class="fa-solid fa-star fa-lg" v-show="company.isFavorite" @click="toggleFavorite(company.code)"></i>
                        <i class="fa-regular fa-star fa-lg" v-show="!company.isFavorite" @click="toggleFavorite(company.code)"></i>
                    </td>
                    <td><a :href="'https://kabutan.jp/stock/chart?code=' + company.code" target="_blank">{{company.code}}</a></td>
                    <td><a :href="'/companydiary?code=' + company.code">{{company.name}}</a></td>
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
</template>

<script>
    export default {
        mounted() {
        },
        data() {
            return {
                keyword: '',
                companies: [],
                isSearched: false,
                lastPage: 1,
                currentPage: 1
            }
        },
        methods: {
            searchCompanies(page) {
                this.currentPage = page;
                this.isSearched = true;

                axios.get('/company/search?page=' + page, { 
                    params: {
                        keyword: this.keyword 
                    }
                })
                .then(res => {
                    this.lastPage = res.data['last_page'];

                    this.companies = [];
                    res.data.data.forEach(d => {
                        // favorite
                        axios.get('/favorite/check', { 
                            params: {
                                code: d['code']
                            }
                        })
                        .then(res2 => {
                            this.companies.push({
                                code: d['code'],
                                name: d['name'],
                                isFavorite: res2.data['state']
                            });
                        })
                        .catch(err2 => {
                            console.log(err2);
                            this.$toast.error(err2);
                        });
                    });
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            clearCompanies() {
                this.keyword = '';
                this.companies = [];
                this.isSearched = false;
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
                            }
                        })
                        .catch(err => {
                            console.log(err);
                            this.$toast.error(err);
                        });
                    }
                });
            },
            clickCallback(num) {
                this.searchCompanies(Number(num));
                window.scrollTo({
                    top: 0,
                    left: 0,
                    //behavior: 'smooth'
                });
            }
        },
        computed: {
            sortCompanies() {
                this.companies.sort((a, b) => {
                    a = a['code'];
                    b = b['code'];
                    return (a === b ? 0 : a > b ? 1 : -1);
                });

                return this.companies;
            },
        }
    }
</script>

<style>
.btn-width {
    width: 100px;
}
</style>