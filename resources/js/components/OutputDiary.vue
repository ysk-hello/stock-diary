<template>
    <h4>{{$t("OutputDiary")}}</h4>
    <div class="row mb-2">
        <div class="col-auto">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="diary" value="diary" v-model="diaryType">
                <label class="form-check-label" for="diary">{{$t("WholeDiary")}}</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="companydiary" value="companydiary" v-model="diaryType">
                <label class="form-check-label" for="companydiary">{{$t("CompanyDiary")}}</label>
            </div>
        </div>
    </div>    
    <div class="row mb-2">
        <div class="col-auto">
            <input class="form-control" type="date" name="startDate" v-model="startDate" @input="inputDate">
        </div>
        <div class="col-auto">
            ～
        </div>
        <div class="col-auto">
            <input class="form-control" type="date" name="endDate" v-model="endDate" @input="inputDate">
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-auto">
            <button type="button" class="btn btn-width" :class="{'btn-success': isActiveW, 'btn-secondary': !isActiveW}" @click="setDate('w')">{{$t("ThisWeek")}}</button>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-width" :class="{'btn-success': isActiveM, 'btn-secondary': !isActiveM}" @click="setDate('m')">{{$t("ThisMonth")}}</button>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-width" :class="{'btn-success': isActiveY, 'btn-secondary': !isActiveY}" @click="setDate('y')">{{$t("ThisYear")}}</button>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-auto">
            <button type="button" class="btn btn-primary btn-width" @click="outputDiaries">{{$t("Output")}}</button>
        </div>
    </div>
</template>

<script>
    import dayjs from 'dayjs';
    import saveAs from 'file-saver';

    dayjs.locale('ja');

    export default {
        mounted() {
        },
        data() {
            return {
                diaryType: 'diary',
                startDate: dayjs().subtract(1, 'w').format('YYYY-MM-DD'),
                endDate: dayjs().format('YYYY-MM-DD'),
                isActiveW: true,
                isActiveM: false,
                isActiveY: false,
            }
        },
        methods: {
            getFileNameFromContentDisposition(disposition) {
                const filenameRegex = /filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/; // 正規表現でfilenameを抜き出す
                const matches = filenameRegex.exec(disposition);
                if (matches != null && matches[1]) {
                    const fileName = matches[1].replace(/['"]/g, '');
                    return decodeURI(fileName) // 日本語対応
                } else {
                    return null
                }
            },
            outputDiaries() {
                // https://tech.adseed.co.jp/php-csv-export
                // https://your-school.jp/laravel-csv-download/293/
                // https://qiita.com/charita/items/5d92f17c5873a7c82b1a
                // https://qiita.com/hiro_nr825/items/63d1d0a20bea3269c37a
                
                let url = '';
                if(this.diaryType === 'diary') {
                    url = '/output/diary';
                }
                else if(this.diaryType === 'companydiary') {
                    url = '/output/companydiary';
                }

                axios.get(url, { 
                    responseType: "blob",
                    params: {
                        startDate: this.startDate,
                        endDate: this.endDate
                    }
                })
                .then(res => {
                    let mineType = res.headers["content-type"];
                    const name = this.getFileNameFromContentDisposition(res.headers["content-disposition"]);
                    const blob = new Blob([res.data], { type: mineType });
                    saveAs(blob, name);

                    this.$toast.success(this.$t("OutputOK"));
                })
                .catch(err => {
                    console.log(err);
                    this.$toast.error(err);
                });
            },
            setDate(period){
                switch(period){
                    case 'w':
                        this.isActiveW = true;
                        this.isActiveM = false;
                        this.isActiveY = false;
                        this.startDate = dayjs().subtract(1, 'w').format('YYYY-MM-DD');
                        this.endDate = dayjs().format('YYYY-MM-DD');
                        break;
                    case 'm':
                        this.isActiveW = false;
                        this.isActiveM = true;
                        this.isActiveY = false;
                        this.startDate = dayjs().startOf('month').format('YYYY-MM-DD');
                        this.endDate = dayjs().format('YYYY-MM-DD');
                        break;
                    case 'y':
                        this.isActiveW = false;
                        this.isActiveM = false;
                        this.isActiveY = true;
                        this.startDate = dayjs().startOf('year').format('YYYY-MM-DD');
                        this.endDate = dayjs().format('YYYY-MM-DD');
                        break;
                }
            },
            inputDate(){
                this.isActiveW = false;
                this.isActiveM = false;
                this.isActiveY = false;
            }
        }
    }
</script>

<style>
.btn-width {
    width: 100px;
}
</style>