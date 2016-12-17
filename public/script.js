csrf = $('meta[name="csrf-token"]').attr('content');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': csrf
    }
});

$('#refinedays .row').removeClass('hidden-default');


var today = new Date();
$(function () {
    $('.input-daterange').datepicker({
        startDate: today
    }).on('changeDate',function(e){
        // bulkOperations.start_date = e.date;
        if(e.target.name == 'start'){
            bulkOperations.start_date = e.date;
        }
        if(e.target.name == 'end'){
            bulkOperations.end_date = e.date;
        }
    })
});

function setEditable(){
    $('.cal-container__cal-editable').editable({
        pk: function () {
            //create variable of current element because
            //$(this) isn't act as expected when I added
            //a new param
            current = $(this);
            return current.attr('data-id');
        },
        url: '/api/vehicles/update',
        ajaxOptions: {
            type: 'put'
        },
        display: false,
        params:{
            month: calendar.data.current_month,
            field: function(){
                return current.attr('data-field');
            }
        }
    }).on('save',function(e, params){
        calendar.data.vehicles = params.response.vehicles;
    });
}

function resetEditable() {
    $('.cal-container__cal-editable').editable('destroy');
    setEditable();
}

Vue.component('editable',{
    props: ['data','name','pk','field'],
    template: '<div class="col-lg-1 cal-container__cal-data cal-container__cal-body"> <a class="cal-container__cal-editable" :data-id="pk" :data-field="field" :data-name="name">{{data}}</a></div>',
    mounted: function(){
        setEditable();
    }

});

$('.cal-container__cal-editable').on('save',function(){

});

Vue.component('calendar-header',{
    props: ['data'],
    template: '<div class="col-lg-1 cal-container__cal-data cal-container__cal-header" :class="is_holiday"><p>{{day_name}}</p></div>',
    computed:{
        day_name: function(){
            return moment(this.data.date).format('ddd');
        },
        is_holiday:function(){
            if(this.day_name == 'Sat'||this.day_name=='Sun'){
                return {
                    'cal-container__cal-header--holiday': true
                }
            }
        }
    }
});

var bulkOperations = new Vue({
    el: ".bo-container",
    data:{
        bulkVisible:false,
        refine_days: 'alldays',
        isVisible: false,
        select_vehicle: [],
        start_date: new Date(),
        end_date: new Date(),
        start_date_formatted: '',
        end_date_formatted: '',
        custom_refine_days: [],
        price: '',
        vehicles_availability: '',
        posting: false,
        updateDisabled: false,
        success_button_visible: false,
        error_list: null
    },
    methods:{
        toggleBulkOn: function(){
            this.bulkVisible=true;
        },
        toggleBulkOff: function(){
            this.bulkVisible=false;
        },
        update: function (e) {
            e.preventDefault();
            this.posting = true;
            this.updateDisabled = true;
            this.$http.put('/api/vehicles/update/bulk', this.data_to_put,{headers:{'X-CSRF-TOKEN':csrf}}).then(function(response){
                this.posting =false;
                this.updateDisabled =false;
                console.log(response);
                k = response;
                console.log('kepo');
                calendar.data.vehicles = response.body.vehicles;
                this.success_button_visible =true;
                setTimeout(function(){
                    this.success_button_visible =false;
                }.bind(this),3000);
            }.bind(this),function(response){
                this.error_list = response.body;
                this.posting =false;
                this.updateDisabled = false;
                console.log(response);
            }).bind(this);
        },
        close_validation_error_box:function(e){
            this.error_list =null;
        }
    },
    computed: {
        custom_refine_days_visible: function(){
            return this.refine_days == 'custom';
        },
        start_date_formatted:function(){
            return moment(this.start_date).format('MM/DD/YYYY');
        },
        end_date_formatted: function(){
            return moment(this.end_date).format('MM/DD/YYYY');
        },
        data_to_put: function(){
            return {
                select_vehicle: this.select_vehicle,
                start_date: this.start_date,
                end_date: this.end_date,
                refine_days: this.refine_days,
                custom_refine_days: this.custom_refine_days,
                price: this.price,
                vehicles_availability: this.vehicles_availability,
                current_month: calendar.data.current_month
            };
        }
    }
});

var calendar = new Vue({
    el: ".cal-container",
    updated: function () {
        this.$nextTick(function () {
            resetEditable();
        })
    },
    data: {
        data: null,
        visible_vehicles_data: null,
        firstItem: 0,
        next_arrow_visible: true,
        previous_arrow_visible: false,
        month: null,
        nextMonthProgress: false,
        previousMonthProgress: false,
        calendarLoaded: false,
        bulk_select_vehicle: [],
        dataToPost: []
    },
    computed: {
        visible_vehicles_data: function(){
            if(this.data != null){
                return this.data.vehicles.slice(this.firstItem, this.firstItem +12);
            }
        },
        previous_arrow_visible: function(){
            return this.firstItem > 0;
        },
        next_arrow_visible: function(){
            if(this.data!=null){
                return this.firstItem +12 < this.data.vehicles.length;
            }
        },
        previous_month_button_visible:function(){
            if(this.data !=null) {
                return this.data.is_this_month != true;
            }
        },
        calendar_current_month: function(){
            if(this.data !=null){
                return moment(this.data.current_month).format('MMMM YYYY');
            }
        }
    },
    methods:{
        nextItem: function(e){
            e.preventDefault();
            this.firstItem++;
        },
        previousItem: function(e){
            e.preventDefault();
            this.firstItem--;
        },
        nextMonth: function(e){
            e.preventDefault();
            this.nextMonthProgress = true;
            this.$http.get(this.data.next_month_url).then(function (response) {
                calendar.data = response.body;
                calendar.visible_vehicles_data = calendar.data.vehicles.slice(0,12);
                this.firstItem= 0;
                this.nextMonthProgress = false;
            });

        },
        previousMonth: function(e){
            e.preventDefault();
            this.previousMonthProgress = true;
            this.$http.get(this.data.previous_month_url).then(function (response) {
                calendar.data = response.body;
                calendar.visible_vehicles_data = calendar.data.vehicles.slice(0,12);
                this.firstItem = 0;
                this.previousMonthProgress = false;
            });
        }
    },
    filters:{
        month: function(date){
            return moment(date).format('MM');
        },
        day: function(date){
            return moment(date).format('DD');
        }
    },
    watch: {

    }
});


Vue.http.get('/api/vehicles').then(function(response){
    calendar.data = response.body;
    calendar.visibleVehiclesData = calendar.data.vehicles.slice(0,12);
    $('.calendar').removeClass('hidden-default');
    calendar.calendarLoaded = true;
});

function refreshCalendar(){
    Vue.http.get(calendar.data.current_month_url).then(function (response) {
        calendar.data = response.body;
        calendar.visibleVehiclesData = calendar.data.vehicles.slice(this.firstItem,this.firstItem + 12);
    });
}

