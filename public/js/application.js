$(function() {
    $(document).ready(function() {
        $('.mySelect').select2({
            search:true,
            width: '100%' // makes it responsive
        });
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#submitBtnRequest').on('click', function (e) {
        e.preventDefault();

        const $btn = $(this);
        $btn.prop('disabled', true); // disable the button to prevent double clicks
        $btn.find('svg').removeClass('hidden'); // show spinner
        $btn.find('.btn-title').text('Submitting...'); // optional: change text

        $('.js-form-request').submit(); // submit the form
    });

    $('.js-area-button').on('click', function (e) {
       localStorage.removeItem('area');
        var _this = $(this);
        var _value = _this.data('value');
        localStorage.setItem('area', _value);
        $('.js-area-form').val(localStorage.getItem('area'));
        $('.js-area-select').addClass('hidden');
        $('.js-form-request-container').removeClass('hidden');
    })

    $('.js-form-back').on('click', function (e) {
        e.preventDefault();
        $('.js-area-select').removeClass('hidden');
        $('.js-form-request-container').addClass('hidden');
    })

    /* Update Loop Number */
    $(document).on('click', '#submitBtnRequestUpdate', function(e) {
        e.preventDefault(); // prevent default form submission
        var _this = $(this);
        _this.attr('disabled', true);
        var _loading =  $(this).find('svg');
        _loading.removeClass('hidden');
        const $form = $(this).closest('form');
        var _devices = []
        console.log($form.find('.js-select-dev').length)
        $form.find('.js-select-dev').each(function(i) {
            var __this = $(this)
            var __parent = __this.closest('fieldset');
            var _remark = __parent.find('.js-remark').val();
            var _po_mr_no = __parent.find('.js_po_mr_no').val();
            var _spec_no = __parent.find('.js_spec_no').val();
            var _loop_dwg = __parent.find('.js_loop_dwg').val();
            var _outsignl = __parent.find('.js_outsignl').val();
            var _range_unit = __parent.find('.js_range_unit').val();
            var _model_type = __parent.find('.js_model_type').val();
            var _manufacturer = __parent.find('.js_manufacturer').val();
            var _device_descrp = __parent.find('.js_device_descrp').val();
            var _dev = __parent.find('.js_dev').val();
            var _supply = __parent.find('.js-supply').val();
            var _id = __parent.find('.js-id-instrument').val();

            _devices.push({
                'remark': _remark,
                'device_descrp': _device_descrp,
                'po_mr_no': _po_mr_no,
                'spec_no': _spec_no,
                'loop_dwg': _loop_dwg,
                'outsignl': _outsignl,
                'range_unit': _range_unit,
                'model_type': _model_type,
                'manufacturer': _manufacturer,
                'dev': _dev,
                'supply': _supply,
                'instrument': _id
            })
        })

        var _pid_dwg = $form.find('.js_pid_dwg').val();
        var _loop_no = $form.find('.js_loop_no').val();
        var _id = $form.find('.js-id-instrument_index').val();
        var _service_id = $form.find('.js-service_id').val();
        var _area_id = $form.find('.js-area_id').val();
        var _ticket_number = $form.find('.js-ticket').val();

        console.log(_devices);

        $.ajax({
            url: '/instrument-index/update', // TODO: replace with your actual Laravel route
            type: 'POST',
            data: {
                'pid_drawing': _pid_dwg,
                'id'  : _id,
                'loop_no': _loop_no,
                'devices' : _devices,
                'service_id' : _service_id,
                'area' : _area_id,
                'ticket_number' : _ticket_number
            },
            success: function(response) {
                // You can handle success message here
                _loading.addClass('hidden');
                $('#success-modal').removeClass('hidden');
                _this.attr('disabled', false);
            },
            error: function(xhr) {
                // Handle error
                alert('Something went wrong.');
                console.error(xhr.responseText);
            }
        });
    });

    $('#closeSuccessBtn').on('click', function (e) {
        $(this).closest('#success-modal').addClass('hidden');
    })

    const toggleBtn = document.getElementById('dropdownToggle');
    const menu = document.getElementById('dropdownMenu');

    document.addEventListener('click', function (e) {
        const isClickInside = toggleBtn.contains(e.target) || menu.contains(e.target);

        if (isClickInside) {
            if (toggleBtn.contains(e.target)) {
                menu.classList.toggle('hidden');
            }
        } else {
            menu.classList.add('hidden');
        }
    });


    function getDataDev(){
        return $.ajax({
            'url' : '/getDataDev',
            'type' : 'GET',
            'dataType' : 'json',
        })
    }
    $(document).on('click', '#add-dev-btn', function (e) {
        e.preventDefault();
        var _this = $(this)
        var _template = $('#js-template-device-info').html()
        getDataDev().then(function(devData) {
            console.log(devData)
            var data = { devOptions: devData };
            var _temp = Mustache.render(_template, data);
            $('.js-temp-dev-here').append(_temp);
        });
    })

    $(document).on('click', '.js-btn-delete-dev-info', function (e) {
        e.preventDefault();
        var _this = $(this)
        var _parent = _this.closest('fieldset');
        _parent.remove();
    })

    $(document).on('click','#finalizeInstrumentIndex', function (e) {
        e.preventDefault();
        var _this = $(this)
        var _ticket = $('.js-ticket').val();
        _this.attr('disabled', true);
        var _loading =  $(this).find('svg');
        _loading.removeClass('hidden');
        $.ajax({
            'url' : '/finalize-instrument-index',
            'data' : {
                'ticket' : _ticket,
            },
            type: 'POST',
            success: function(response) {
                _loading.addClass('hidden');
                $('#success-modal').removeClass('hidden');
                _this.attr('disabled', false);
            }
        })
    })

})

