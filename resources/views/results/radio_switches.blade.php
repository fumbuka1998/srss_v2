<div class="form-group-inner">
    <div class="row">
        <div class="col-lg-9 col-md-6 col-sm-6 col-xs-12">
            <div class="pull-left radios-flex">
                <div class="row" style="margin-right: 0.5rem">
                    <div class="col-lg-12">
                        <div class="i-checks pull-left">
                            <label>
                                <input class="radio" type="radio" {{ $activeRadio == 'template' ? 'checked' : '' }}  value="template" name="a"> <i></i> By Excel </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="i-checks pull-left">
                            <label>
                                <input class="radio" type="radio" {{ $activeRadio == 'system' ? 'checked' : '' }}  value="system" name="a"> <i></i> By System </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
