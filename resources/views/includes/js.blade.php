<script type="text/javascript" src="{{ asset('assets/scripts/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/scripts/custom.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/scripts/new/jquery.cookie.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/scripts/new/swiper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/scripts/new/nouislider.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/scripts/new/main.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/scripts/new/color-scheme-demo.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/scripts/new/pwa-services.js') }}"></script>
<script src="{{asset('admin/plugins/select2/select2.full.min.js')}}"></script>
<script src="{{asset('admin/js/persian-date.min.js')}}"></script>
<script src="{{asset('admin/js/persian-datepicker.min.js')}}"></script>
<script>
    "use strict"
    $(window).on('load', function() {

        /* range picker for filter */
        var html5Slider = document.getElementById('rangeslider');
        noUiSlider.create(html5Slider, {
            start: [0, 100],
            connect: true,
            range: {
                'min': 0,
                'max': 500
            }
        });

        var inputNumber = document.getElementById('input-number');
        var select = document.getElementById('input-select');

        html5Slider.noUiSlider.on('update', function(values, handle) {
            var value = values[handle];

            if (handle) {
                inputNumber.value = value;
            } else {
                select.value = Math.round(value);
            }
        });
        select.addEventListener('change', function() {
            html5Slider.noUiSlider.set([this.value, null]);
        });
        inputNumber.addEventListener('change', function() {
            html5Slider.noUiSlider.set([null, this.value]);
        });


        /* carousel */
        var swiper = new Swiper('.swiper-products', {
            slidesPerView: 'auto',
            spaceBetween: 0,
            pagination: 'false'
        });

    });
</script>
{{-- @if (auth()->user())
    <script>
        function loadDoc() {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                console.log( this.responseText );
            }
            xhttp.open("GET", '{{url("/")."/update-roll-call"}}');
            xhttp.send();
            setTimeout(loadDoc, 100000);
        }
        loadDoc();
    </script>
@endif --}}
<script>
    setTimeout(function() { $(".alert").alert('close') }, 5000);
    $(function () { $('.select2').select2() });
    $('.carousel').carousel({ interval: 3000 })
    $('.date_p').persianDatepicker({
        observer: true,
        format: 'YYYY/MM/DD',
        altField: '.observer-example-alt',
        initialValue:false,
    }); 
    // $('.check-date-example').persianDatepicker({
    //     checkDate: function(unix){
    //         return new persianDate(unix).day() != 4;
    //     }
    // });
    $(document).ready(function () {
        $('select[name=state_id]').on('change', function () {
            $.get("{{url('/')}}/city-ajax/" + $(this).val(), function (data, status) {
                $('select[name=city_id]').empty();
                $.each(data, function (key, value) {
                    $('select[name=city_id]').append('<option value="' + value.id + '">' + value.name + '</option>');
                });
                $('select[name=city_id]').trigger('change');
            });
        });
    });
</script>
<script>
    function setInputWork(name) {
        if (document.getElementById(name).checked) {
            document.getElementById(`Inp${name}`).value = 'active';
            document.getElementById(`Lab${name}`).classList.add("text-success");
            
        } else {
            document.getElementById(`Inp${name}`).value = 'deactive';
            document.getElementById(`Lab${name}`).classList.remove("text-success");
        }
    }
    
    @if ($runningJob??'')

        setInterval(timer2, 60000);
        
        function timer() {
            var time = parseInt('{{$runningJob->startTime}}');
            var hour = parseInt(time / 60);
            var min  = parseInt(time % 60);

            if (min==60) {
                hour = hour+1;
                min = '00'
            } else if (min < 10) {
                min = `0${min}`;
            }

            if (hour < 10) {
                document.getElementById('runningJobTimer').innerHTML = `${min} : 0${hour}`;
            } else {
                document.getElementById('runningJobTimer').innerHTML = `${min} : ${hour}`;
            }
            timer2();
        }
        function timer2() {
            var hour = parseInt(document.getElementById('runningJobTimer').innerHTML.substr(5, 2));
            var min  = parseInt(document.getElementById('runningJobTimer').innerHTML.substr(0, 2));

            min = min+1;

            if (min==60) {
                hour = hour+1;
                min = '00'
            } else if (min < 10) {
                min = `0${min}`;
            }

            if (hour < 10) {
                document.getElementById('runningJobTimer').innerHTML = `${min} : 0${hour}`;
            } else {
                document.getElementById('runningJobTimer').innerHTML = `${min} : ${hour}`;
            }
        }
        timer();
        
    @endif
</script>
@yield('js')
