<!DOCTYPE html>
<html>
<head>
    <title>Logasia</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://opensource.keycdn.com/fontawesome/4.7.0/font-awesome.min.css" integrity="sha384-dNpIIXE8U05kAbPhy3G1cz+yZmTzA6CY8Vg/u2L9xRnHjJiAK76m2BIEaSEV+/aU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css">
    <link rel="stylesheet" href="/css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>

    <script src="https://unpkg.com/vue@2.1.4/dist/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="/js/moment.min.js"></script>
</head>
<body>

<div class="container">
    <div class="bo-container">
        <div>
            <button class="btn btn-default top-margin-50" v-on:click="toggleBulkOn" v-if="!bulkVisible">Bulk Operations</button>

        </div>

        <div class="default-box hidden-default" :class="{'visible':bulkVisible}">
            <div class="bo-container__title-box bo-container__item-box">
                <p class="bo-container__title-box__title"> <span>Bulk Operations</span></p>
            </div>
            <div class="bo-container__item-box bo-container__item-box--light">
                <div class="row bo-container__form-field">
                    <div class="col-lg-2">
                        <p> <strong>Select vehicle(s):</strong></p>
                    </div>
                    <div class="col-lg-10">
                        <select class="selectpicker" name="" id="" multiple="" data-selected-text-format="count" v-model="select_vehicle">
                            <option value="semi_trailer_truck">Semi-trailer Truck</option>
                            <option value="swap_body_truck">20 foot swap-body truck</option>
                            <option value="pup_trailer">28.5 foot pup trailer</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="bo-container__item-box">
                <div class="row bo-container__form-field">
                    <div class="col-lg-2">
                        <p> <strong>Select days:</strong></p>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-inline">
                            <div class="input-daterange">
                                <div class="row bottom-padding">
                                    <div class="col-lg-4">
                                        <label for="start">From :</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input class="form-control" type="text" name="start" v-model="start_date_formatted">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <label for="end">To :</label>
                                    </div>
                                    <div class="col-lg-8">
                                        <input class="form-control" type="text" name="end" v-model="end_date_formatted">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-lg-3">
                                <p>Refine days:</p>
                            </div>
                            <div class="col-lg-9">
                                <select class="selectpicker" name="refine_days" v-model="refine_days">
                                    <option value="alldays">All days</option>
                                    <option value="weekdays">Weekdays</option>
                                    <option value="weekend">Weekend</option>
                                    <option value="custom">Custom</option>
                                </select>
                                <div class="row top-padding" v-if="custom_refine_days_visible">
                                    <div class="col-lg-4">
                                        <label>
                                            <input type="checkbox" value="0" v-model="custom_refine_days"> Mondays<br>
                                        </label>
                                        <label>
                                            <input type="checkbox" value="3" v-model="custom_refine_days"> Thursdays<br>
                                        </label>
                                        <label>
                                            <input type="checkbox" value="6" v-model="custom_refine_days"> Sundays
                                        </label>
                                    </div>
                                    <div class="col-lg-4">
                                        <label>
                                            <input type="checkbox" value="1" v-model="custom_refine_days"> Tuesdays    <br>
                                        </label>
                                        <label>
                                            <input type="checkbox" value="4" v-model="custom_refine_days"> Fridays
                                        </label>
                                    </div>
                                    <div class="col-lg-4">
                                        <label>
                                            <input type="checkbox" value="2" v-model="custom_refine_days"> Wednesdays<br>
                                        </label>
                                        <label>
                                            <input type="checkbox" value="5" v-model="custom_refine_days"> Saturdays
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bo-container__item-box bo-container__item-box--light">
                <div class="row bottom-padding">
                    <div class="col-lg-2">
                        <p> <strong>Change price to:</strong></p>
                    </div>
                    <div class="col-lg-2">
                        <input class="form-control" type="number" min="0" v-model="price">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <p> <strong>Change availability to:</strong></p>
                    </div>
                    <div class="col-lg-2">
                        <input class="form-control" type="number" min="0" v-model="vehicles_availability">
                    </div>
                </div>
            </div>
            <div class="bo-container__item-box">
                <div class="row">
                    <div class="col-lg-12">
                        <button class="btn" v-on:click="toggleBulkOff">Cancel</button>
                        <button class="btn btn-primary left-margin" v-on:click="update" v-bind:disabled="updateDisabled"><i class="fa fa-spinner fa-spin" v-if="posting" ></i> Update</button>
                        <span class="bo-container__update-notification bo-container__update-notification--success" v-if="success_button_visible"><i class="fa fa-check"></i> Update success</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="default-box cal-container">
        <div class="beforeCalendar">
            <p class="text-center top-padding bottom-padding" :class="{hidden:calendarLoaded}"> <i class="fa fa-spinner fa-spin" ></i> Loading data, please wait...</p>
        </div>
        <div class="calendar hidden-default" :class="{visible:calendarLoaded}" v-if="calendarLoaded">
            <div class="row cal-container__item-box top-padding-10">
                <div class="col-lg-offset-2 col-lg-10">
                    <p class="text-center relative"><a class="cal-container__left-arrow" v-on:click="previousItem" v-if="previous_arrow_visible" href="#"><i class="fa fa-arrow-left" ></i></a><strong>@{{calendar_current_month}}   </strong><a class="cal-container__right-arrow" v-on:click="nextItem" v-if="next_arrow_visible" href="#"><i class="fa fa-arrow-right"></i></a><a class="cal-container__previous-month" href="#" v-if="previous_month_button_visible" v-on:click="previousMonth">Previous Month <i class="fa fa-spinner fa-spin" v-if="previousMonthProgress"></i></a><a class="cal-container__next-month" href="#" v-on:click="nextMonth"><i class="fa fa-spinner fa-spin" v-if="nextMonthProgress"></i> Next Month </a></p>
                </div>
            </div>
            <div class="row cal-container__item-box top-margin">
                <div class="col-lg-2">
                    <p class="cal-container__cal-data-title"><strong>Price and availability</strong></p>
                </div>
                <div class="col-lg-10">
                    <div class="row" >
                        <calendar-header v-for="data in visible_vehicles_data"  :data="data"></calendar-header>
                    </div>
                </div>
            </div>
            <div class="row cal-container__item-box">
                <div class="col-lg-offset-2 col-lg-10">
                    <div class="row">
                        <div class="col-lg-1 cal-container__cal-data cal-container__cal-body" v-for="data in visible_vehicles_data">@{{data.date|day}}</div>
                    </div>
                </div>
            </div>
            <div class="row cal-container__item-box cal-container__section-divider cal-container__section-divider--chocolate">
                <div class="col-lg-2">
                    <p><strong>Semi-trailer truck</strong></p>
                </div>
            </div>
            <div class="row cal-container__item-box">
                <div class="col-lg-2">
                    <p class="cal-container__cal-data-title">Vehicles available</p>
                </div>
                <div class="col-lg-10">
                    <div class="row">
                        <editable name="semi_trailer_truck" field="vehicles_available" v-for="data in visible_vehicles_data" :data="data.semi_trailer_truck.vehicles_available" :pk="data.semi_trailer_truck.id" ></editable>
                    </div>
                </div>
            </div>
            <div class="row cal-container__item-box">
                <div class="col-lg-2">
                    <p class="cal-container__cal-data-title"> Price (USD)</p>
                </div>
                <div class="col-lg-10">
                    <div class="row">
                        <editable  name="semi_trailer_truck" field="price" v-for="data in visible_vehicles_data" :data="data.semi_trailer_truck.price" :pk="data.semi_trailer_truck.id"></editable>
                    </div>
                </div>
            </div>
            <div class="row cal-container__item-box cal-container__section-divider cal-container__section-divider--light-blue">
                <div class="col-lg-2">
                    <p> <strong>20 foot swap-body truck</strong></p>
                </div>
            </div>
            <div class="row cal-container__item-box">
                <div class="class col-lg-2">
                    <p class="cal-container__cal-data-title"> Vehicles available</p>
                </div>
                <div class="col-lg-10">
                    <div class="row">
                        <editable  name="swap_body_truck" field="vehicles_available" v-for="data in visible_vehicles_data" :data="data.swap_body_truck.vehicles_available" :pk="data.swap_body_truck.id"></editable>
                    </div>
                </div>
            </div>
            <div class="row cal-container__item-box">
                <div class="col-lg-2">
                    <p class="cal-container__cal-data-title"> Price (EUR)</p>
                </div>
                <div class="col-lg-10">
                    <div class="row">
                        <editable  name="swap_body_truck" field="price" v-for="data in visible_vehicles_data" :data="data.swap_body_truck.price" :pk="data.swap_body_truck.id"></editable>
                    </div>
                </div>
            </div>
            <div class="row cal-container__item-box cal-container__section-divider cal-container__section-divider--light-green">
                <div class="col-lg-2">
                    <p> <strong>28.5 foot pup trailer</strong></p>
                </div>
            </div>
            <div class="row cal-container__item-box">
                <div class="class col-lg-2">
                    <p class="cal-container__cal-data-title"> Vehicles available</p>
                </div>
                <div class="col-lg-10">
                    <div class="row">
                        <editable  name="pup_trailer" field="vehicles_available" v-for="data in visible_vehicles_data" :data="data.pup_trailer.vehicles_available" :pk="data.pup_trailer.id"></editable>
                    </div>
                </div>
            </div>
            <div class="row cal-container__item-box">
                <div class="col-lg-2">
                    <p class="cal-container__cal-data-title"> Price (EUR)</p>
                </div>
                <div class="col-lg-10">
                    <div class="row">
                        <editable  name="pup_trailer" field="price" v-for="data in visible_vehicles_data" :data="data.pup_trailer.price" :pk="data.pup_trailer.id"></editable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script src="https://cdn.jsdelivr.net/vue.resource/1.0.3/vue-resource.min.js"></script>
<script src="script.js"></script>
</body>
</html>