<script id="js-template-device-info" type="x-tmpl-mustache">
    <fieldset>
        <legend>Device Info</legend>
        <div class="float-end m-2 text-red-500"><button class="js-btn-delete-dev-info">Delete</button></div>
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="form-group">
                <label for="dev">DEV</label>
                <select name="dev" id="#mySelect"
                        class="js-select-dev w-full px-4 py-2 border border-teal-300 js_dev rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500"
                        required>
                    @{{#devOptions}}
                        <option value="@{{code}}" data-description="@{{description}}" @{{#selected}}selected@{{/selected}}>@{{code}}</option>
                    @{{/devOptions}}
                </select>
                <div class="form-field"><label for="device_descrp">Device Description</label><input type="text" class="js_device_descrp" value="" name="device_descrp" /></div>
                <div class="form-field"><label for="manufacturer">Manufacturer</label>
                <select class="js_manufacturer w-full px-4 py-2 border border-teal-300 js_dev rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" name="manufacturer">
                    @{{#manufacturer}}
                       <option value="@{{setting_value}}" @{{#selected}}selected@{{/selected}}>@{{setting_value}}</option>
                    @{{/manufacturer}}
                </select>
                </div>
                <div class="form-field"><label for="model_type">Model/Element Type</label><input type="text" class="js_model_type" value="" name="model_type" /></div>
                <div class="form-field"><label for="range_unit">Range/Unit</label><input type="text" class="js_range_unit" value="" name="range_unit" /></div>
                <div class="form-field"><label for="outsignl">Output Signal</label>
                <select class="js_outsignl w-full px-4 py-2 border border-teal-300 js_dev rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" name="outsignl">
                    @{{#outSignalOptions}}
                       <option value="@{{setting_value}}" @{{#selected}}selected@{{/selected}}>@{{setting_value}}</option>
                    @{{/outSignalOptions}}
                </select>
                </div>
                <div class="form-field w-full"><label for="supply">Supply</label>
                <select class="js-supply w-full px-4 py-2 border border-teal-300 js_dev rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500" name="supply">
                    @{{#supply}}
                       <option value="@{{setting_value}}" @{{#selected}}selected@{{/selected}}>@{{setting_value}}</option>
                    @{{/supply}}
                </select>
                </div>
                <div class="form-field"><label for="loop_dwg">Loop Drawing</label><input type="text" value="" class="js_loop_dwg" name="loop_dwg" /></div>
                <div class="form-field"><label for="spec_no">Spec No</label><input type="text" value="" class="js_spec_no" name="spec_no" /></div>
                <div class="form-field"><label for="po_mr_no">PO/MR No</label><input type="text" value="" class="js_po_mr_no" name="po_mr_no" /></div>
                <div class="form-field w-full"><label for="remark">Remark</label><textarea class="js-remark p-2" name="remark" rows="4"></textarea></div>
            </div>
        </div>
    </fieldset>
</script>
