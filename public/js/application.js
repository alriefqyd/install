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
        var _remark = $form.find('.js-remark').val();
        var _po_mr_no = $form.find('.js_po_mr_no').val();
        var _spec_no = $form.find('.js_spec_no').val();
        var _loop_dwg = $form.find('.js_loop_dwg').val();
        var _outsignl = $form.find('.js_outsignl').val();
        var _range_unit = $form.find('.js_range_unit').val();
        var _model_type = $form.find('.js_model_type').val();
        var _manufacturer = $form.find('.js_manufacturer').val();
        var _device_descrp = $form.find('.js_device_descrp').val();
        var _pid_dwg = $form.find('.js_pid_dwg').val();
        var _loop_no = $form.find('.js_loop_no').val();
        var _dev = $form.find('.js_dev').val();
        var _supply = $form.find('.js-supply').val();
        var _id = $form.find('.js-id-instrument_index').val();

        $.ajax({
            url: '/instrument-index/update', // TODO: replace with your actual Laravel route
            type: 'POST',
            data: {
                'remark': _remark,
                'supply' : _supply,
                'po_mr_no': _po_mr_no,
                'spec_no': _spec_no,
                'loop_no': _loop_no,
                'loop_drwg': _loop_dwg,
                'outsignal': _outsignl,
                'range_unit': _range_unit,
                'manufacturer': _manufacturer,
                'model': _model_type,
                'device_description': _device_descrp,
                'pid_drawing': _pid_dwg,
                'dev' : _dev,
                'id'  : _id
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

})

