@extends('main')
@section('main')
<!-- Wave background -->
<div class="relative w-full min-h-screen overflow-visible">
    <!-- Yellow background wave -->
    <!-- Yellow background wave -->
    <!-- Yellow background wave -->
    <svg class="absolute top-0 left-0 w-full h-[120vh] z-0" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path fill="#facc15" fill-opacity="1"
              d="M0,80 C360,160 1080,40 1440,120 L1440,0 L0,0 Z"></path>
    </svg>

    <!-- Teal foreground wave -->
    <svg class="absolute top-0 left-0 w-full h-[120vh] z-10" viewBox="0 0 1440 320" preserveAspectRatio="none">
        <path fill="#0f766e" fill-opacity="1"
              d="M0,79 C360,158 1080,38 1440,118 L1440,0 L0,0 Z"></path>
    </svg>



    <div class="relative w-full flex flex-col items-center justify-start pt-24 px-4 z-20">

        <div class="text-center mb-6">
            <h1 class="text-4xl font-bold text-white">Instrument Index</h1>
            <p class="text-lg text-white">Update your instrument index</p>
        </div>

        <!-- Tabs -->
        <div class="w-full max-w-7xl mx-auto">
            <div class="bg-white p-10 rounded-2xl shadow-xl mb-10 flex">
                @if(sizeof($instrumentIndex) > 0)
                    <!-- Vertical Tab Buttons (Sidebar) -->
                    <div class="flex flex-col gap-2 w-60 pr-6 border-r border-gray-200">
                        @foreach($instrumentIndex as $idx)
                            <button class="tab-btn px-4 py-2 rounded-md border border-gray-300 bg-white shadow-sm hover:bg-teal-50 hover:text-teal-700 {{$loop->index == 0 ? 'active-tab' : ''}}" data-tab="{{$idx->id}}">Loop No <span class="text-yellow-400"> {{$idx->code}} </span></button>
                        @endforeach
                    </div>

                    <!-- Tab Contents -->
                    <div class="flex-1 pl-6">
                        @foreach($instrumentIndex as $idx)
                        <div class="tab-content {{$loop->index >0 ? "hidden" : ""}}" id="{{$idx->id}}" >
                            <form action="" method="POST" enctype="multipart/form-data" class="flex-1 pl-6">
                                @csrf
                                <input type="hidden" class="js-id-instrument_index" name="id" value="{{$idx->id}}">
                                <!-- Device Info -->
                                <fieldset>
                                    <legend>Device Info</legend>
                                    <div class="form-group">
                                        <div class="form-field"><label for="dev">DEV</label>
                                            <select name="dev" id="#mySelect" class="w-full px-4 py-2 border border-teal-300 js_dev rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                                                @foreach($dev as $a)
                                                    <option {{$a->code == $idx->dev ? 'selected' : ''}} value="{{$a->code}}">[{{$a->code}}]-{{$a->description}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-field"><label for="loop_no">Loop No</label><input type="text" class="js_loop_no" name="loop_no" value="{{$idx->code}}"/></div>
                                        <div class="form-field"><label for="service">Service</label>
                                            <select name="area_id" class="w-full px-4 py-2 border border-teal-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" required>
                                                @foreach($services as $a)
                                                    <option {{$a->id == $idx->service_id ? 'selected' : ''}} value="{{$a->id}}">{{$a->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-field"><label for="pid_dwg">P&ID Drawing</label><input type="text" class="js_pid_dwg" value="{{$idx->pid_drawing}}" name="pid_dwg" /></div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend>Specifications</legend>
                                    <div class="form-group">
                                        <div class="form-field"><label for="device_descrp">Device Description</label><input type="text" class="js_device_descrp" value="{{$idx->device_description}}" name="device_descrp" /></div>
                                        <div class="form-field"><label for="manufacturer">Manufacturer</label><input type="text" class="js_manufacturer" value="{{$idx->manufacturer}}" name="manufacturer" /></div>
                                        <div class="form-field"><label for="model_type">Model/Element Type</label><input type="text" class="js_model_type" value="{{$idx->model}}" name="model_type" /></div>
                                        <div class="form-field"><label for="range_unit">Range/Unit</label><input type="text" class="js_range_unit" value="{{$idx->range_unit}}" name="range_unit" /></div>
                                    </div>
                                </fieldset>
                                <!-- Signal and Drawing -->
                                <fieldset>
                                    <legend>Signal & Loop Drawing</legend>
                                    <div class="form-group">
                                        <div class="form-field"><label for="outsignl">Output Signal</label><input type="text" value="{{$idx->outsignal}}" class="js_outsignl" name="outsignl" /></div>
                                        <div class="form-field w-full"><label for="supply">Supply</label><input class="js-supply" type="text" value="{{$idx->supply ?? ""}}" name="supply"/></div>
                                        <div class="form-field"><label for="loop_dwg">Loop Drawing</label><input type="text" value="{{$idx->loop_drwg}}" class="js_loop_dwg" name="loop_dwg" /></div>
                                    </div>
                                </fieldset>

                                <!-- Procurement -->
                                <fieldset>
                                    <legend>Procurement Info</legend>
                                    <div class="form-group">
                                        <div class="form-field"><label for="spec_no">Spec No</label><input type="text" value="{{$idx->spec_no}}" class="js_spec_no" name="spec_no" /></div>
                                        <div class="form-field"><label for="po_mr_no">PO/MR No</label><input type="text" value="{{$idx->po_mr_no}}" class="js_po_mr_no" name="po_mr_no" /></div>
                                    </div>
                                </fieldset>

                                <!-- Remarks -->
                                <fieldset>
                                    <legend>Remarks</legend>
                                    <div class="form-group">
                                        <div class="form-field w-full"><label for="remark">Remark</label><textarea class="js-remark p-2" name="remark" rows="4">{{$idx->remark}}</textarea></div>
                                    </div>
                                </fieldset>

                                <!-- Other fieldsets (Specifications, Signal, etc.) go here -->
                                <div class="submit-btn float-end">
                                    <button
                                        type="submit"
                                        id="submitBtnRequestUpdate"
                                        class="flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-teal-700 text-white font-semibold hover:bg-teal-800 disabled:opacity-50"
                                    >
                                        <svg
                                            id="spinner"
                                            class="w-4 h-4 animate-spin text-white hidden"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                        >
                                            <circle
                                                class="opacity-25"
                                                cx="12"
                                                cy="12"
                                                r="10"
                                                stroke="currentColor"
                                                stroke-width="4"
                                            ></circle>
                                            <path
                                                class="opacity-75"
                                                fill="currentColor"
                                                d="M4 12a8 8 0 018-8v8H4z"
                                            ></path>
                                        </svg>
                                        <span class="btn-title"> Submit Request</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endforeach
                    </div>
                @else
                    Looks Like your data already expired
                @endif
            </div>
        </div>

    </div>

    <div id="success-modal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl p-6 shadow-xl w-full max-w-sm text-center">
            <div class="flex justify-center mb-4">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">Success!</h2>
            <p class="text-gray-600 mb-6">Your data has been saved successfully.</p>

            <button id="closeSuccessBtn" class="px-4 py-2 rounded-lg bg-green-600 text-white hover:bg-green-700">
                OK
            </button>
        </div>
    </div>
</div>
@endsection


