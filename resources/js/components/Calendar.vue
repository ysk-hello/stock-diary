<template>
    <h5 class="mt-3">
        <span>{{selectedDate.format('YYYY-MM-DD')}}</span>
        <div>
            <a :href="prev2()"><i class="fa-solid fa-angles-left"></i></a>
            <a :href="prev()" class="mx-3"><i class="fa-solid fa-angle-left"></i></a>
            <a :href="toToday()" id="today-link">{{ $t("Today") }}</a>
            <a :href="next()" class="mx-3"><i class="fa-solid fa-angle-right"></i></a>
            <a :href="next2()"><i class="fa-solid fa-angles-right"></i></a>
        </div>
    </h5>
    <table class="table table-sm table-bordered" id="calendar-table">
        <thead>
            <tr>
                <td>{{ $t("Sun") }}</td>
                <td>{{ $t("Mon") }}</td>
                <td>{{ $t("Tue") }}</td>
                <td>{{ $t("Wed") }}</td>
                <td>{{ $t("Thu") }}</td>
                <td>{{ $t("Fri") }}</td>
                <td>{{ $t("Sat") }}</td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="week in weeks">
                <!-- https://teratail.com/questions/178689 -->
                <td :class="{today: week.sun.today, selected: week.sun.selected}" @click="dayClicked">{{week.sun.date}}</td>
                <td :class="{today: week.mon.today, selected: week.mon.selected}" @click="dayClicked">{{week.mon.date}}</td>
                <td :class="{today: week.tue.today, selected: week.tue.selected}" @click="dayClicked">{{week.tue.date}}</td>
                <td :class="{today: week.wed.today, selected: week.wed.selected}" @click="dayClicked">{{week.wed.date}}</td>
                <td :class="{today: week.thu.today, selected: week.thu.selected}" @click="dayClicked">{{week.thu.date}}</td>
                <td :class="{today: week.fri.today, selected: week.fri.selected}" @click="dayClicked">{{week.fri.date}}</td>
                <td :class="{today: week.sat.today, selected: week.sat.selected}" @click="dayClicked">{{week.sat.date}}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    import dayjs from 'dayjs';
    dayjs.locale('ja');

    export default {
        created() {
            let searchParams = new URLSearchParams(window.location.search);
            this.code = searchParams.get('code');

            if(searchParams.has('ymd')){
                this.selectedDate = dayjs(searchParams.get('ymd'));
            }
            else{
                this.selectedDate = dayjs();
            }
        },
        mounted() {
            this.setCalendarLines();
        },
        data() {
            return {
                code: '',
                selectedDate: null,
                weeks: []
            }
        },
        methods: {
            setCalendarLines() {
                // 初日の曜日を計算
                // https://zenn.dev/akkie1030/articles/javascript-dayjs
                let dayOfWeek = this.selectedDate.startOf('month').format('d');

                // 月の日数を計算
                let count = this.selectedDate.endOf('month').format('D');

                this.weeks = [];
                let day = 1;
                while(day<=count){
                    let week = {
                        sun: {
                            date: '',
                            selected: false,
                            today: false,
                        },
                        mon: {
                            date: '',
                            selected: false,
                            today: false,
                        },
                        tue: {
                            date: '',
                            selected: false,
                            today: false,
                        },
                        wed: {
                            date: '',
                            selected: false,
                            today: false,
                        },
                        thu: {
                            date: '',
                            selected: false,
                            today: false,
                        },
                        fri: {
                            date: '',
                            selected: false,
                            today: false,
                        },
                        sat: {
                            date: '',
                            selected: false,
                            today: false,
                        },
                    };

                    while(dayOfWeek % 7 < 7){
                        switch(dayOfWeek % 7){
                            case 0:
                                week.sun.date = day;
                                week.sun.today = this.isToday(day);
                                week.sun.selected = this.isSelected(day);
                                break;
                            case 1:
                                week.mon.date = day;
                                week.mon.today = this.isToday(day);
                                week.mon.selected = this.isSelected(day);
                                break;
                            case 2:
                                week.tue.date = day;
                                week.tue.today = this.isToday(day);
                                week.tue.selected = this.isSelected(day);
                                break;
                            case 3:
                                week.wed.date = day;
                                week.wed.today = this.isToday(day);
                                week.wed.selected = this.isSelected(day);
                                break;
                            case 4:
                                week.thu.date = day;
                                week.thu.today = this.isToday(day);
                                week.thu.selected = this.isSelected(day);
                                break;
                            case 5:
                                week.fri.date = day;
                                week.fri.today = this.isToday(day);
                                week.fri.selected = this.isSelected(day);
                                break;
                            case 6:
                                week.sat.date = day;
                                week.sat.today = this.isToday(day);
                                week.sat.selected = this.isSelected(day);
                                break;
                        }

                        dayOfWeek++;
                        day++;
                        if(dayOfWeek % 7 === 0 || count < day) break;
                    }

                    this.weeks.push(week);
                }
            },
            isToday(day){
                // 本日
                let today = dayjs().format('YYYY-MM-DD');
                return this.selectedDate.date(day).format('YYYY-MM-DD') === today;
            },
            isSelected(day){
                // 選択日
                let selected = this.selectedDate.format('YYYY-MM-DD');
                return this.selectedDate.date(day).format('YYYY-MM-DD') === selected;
            },
            dayClicked(e) {
                let date = e.target.innerText;

                if(date){
                    this.selectedDate = this.selectedDate.date(date);

                    let uRLSearchParams = new URLSearchParams();
                    uRLSearchParams.append('ymd', this.selectedDate.format('YYYY-MM-DD'));
                    if(this.code) {
                        uRLSearchParams.append('code', this.code);
                    }

                    location.href =  '?' + uRLSearchParams.toString();
                }
            },
            getUrl(date){
                if(this.code){
                    return '?ymd=' + date + '&code=' + this.code;
                }
                else{
                    return '?ymd=' + date;
                }
            },
            prev() {
                const date = this.selectedDate.subtract(1, 'M').format('YYYY-MM-DD');
                return this.getUrl(date);
            },
            next() {
                const date = this.selectedDate.add(1, 'M').format('YYYY-MM-DD');
                return this.getUrl(date);
            },
            prev2() {
                const date = this.selectedDate.subtract(1, 'y').format('YYYY-MM-DD');
                return this.getUrl(date);
            },
            next2() {
                const date = this.selectedDate.add(1, 'y').format('YYYY-MM-DD');
                return this.getUrl(date);
            },
            toToday() {
                const date = dayjs().format('YYYY-MM-DD');
                return this.getUrl(date);
            }
        },
    }
</script>

<style>
#today-link {
    text-decoration: none;
}
#calendar-table {
    margin: 0 auto;
}
#calendar-table th, 
#calendar-table td {
    text-align: center;
}
#calendar-table th:nth-of-type(1),
#calendar-table td:nth-of-type(1) {
    color: red;
}
#calendar-table th:nth-of-type(7),
#calendar-table td:nth-of-type(7) {
    color: blue;
}
#calendar-table .today {
    text-decoration: underline;
}
#calendar-table .holiday {
    color: red;
}
#calendar-table .selected {
    border: 3px solid red;
}
</style>