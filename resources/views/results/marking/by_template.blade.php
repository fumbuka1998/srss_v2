<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading accordion-head">
                <h4 class="panel-title">
     <a data-toggle="collapse" data-parent="#accordion2" href="#collapse4" aria-expanded="false" class="collapsed"><i class="fa-solid fa-file-excel" style="color: cadetblue"></i> Download Template</a>
  </h4>
            </div>
            <div id="collapse4" class="panel-collapse panel-ic collapse" aria-expanded="false" style="height: 0px;">
                <div class="panel-body admin-panel-content animated flash">
                    <div class="row" style="margin: 0.5rem 1rem">

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Class</label>
                                <select name="class" class="form-control select2_demo_3" id="class" style="width: 100%;">
                                    <option value=""></option>
                                    @foreach ($classes as $class )
                                    <option value="{{$class->id}}">{{$class->name}}</option>
                                    @endforeach
                                </select>
                        </div>
                        </div>


                        <div class="col-md-3">

                            <div class="form-group">
                                <label for="">Stream</label>
                                <select name="stream" class="form-control select2_demo_3" id="stream" style="width: 100%;">

                                </select>

                        </div>

                        </div>


                         <div class="col-md-3">

                            <div class="form-group">
                                <label for="">Subject</label>
                                <select name="subjects" class="form-control select2_demo_3" id="subjects" style="width: 100%;">

                                </select>

                        </div>

                        </div>



                        <div class="col-md-3" style="display: flex; justify-content:start; margin-top: 2.4rem;">

                            <span>
                                <a href="javascript:void(0)" onclick="generateFile('excel')" type="button" style="background: #5cb85b" class="btn btn-custon-rounded-four btn-icon btn-success">
                                    <i class="fa-solid fa-cloud-arrow-down"></i> Template
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            </div>





            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading accordion-head">
                        <h4 class="panel-title">
             <a data-toggle="collapse" data-parent="#accordion2" href="#upload" aria-expanded="false" class="collapsed"> <i class="fa-solid fa-square-poll-vertical" style="color: #008080;"></i> Upload Results </a>
          </h4>
                    </div>
                    <div id="upload" class="panel-collapse panel-ic collapse" aria-expanded="false" style="height: 0px;">
                        <div class="panel-body admin-panel-content animated flash">
                            <div class="row" style="margin: 0.5rem 1rem">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Academic Year</label>
                                        <select name="class" class="form-control select2_demo_3" id="class" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach ($classes as $class )
                                            <option value="{{$class->id}}">{{$class->name}}</option>
                                            @endforeach
                                        </select>

                                    </div>

                                </div>



                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Term</label>
                                        <select name="class" class="form-control select2_demo_3" id="class" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach ($classes as $class )
                                            <option value="{{$class->id}}">{{$class->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>



                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Class</label>
                                    <select name="class" class="form-control select2_demo_3" id="class" style="width: 100%;">
                                        <option value=""></option>
                                        @foreach ($classes as $class )
                                        <option value="{{$class->id}}">{{$class->name}}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Stream</label>
                                    <select name="stream" class="form-control select2_demo_3" id="stream" style="width: 100%;">

                                    </select>
                                </div>
                            </div>


                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Subject</label>
                                    <select name="subjects" class="form-control select2_demo_3" id="subjects" style="width: 100%;">
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Exam Type</label>
                                    <select name="subjects" class="form-control select2_demo_3" id="subjects" style="width: 100%;">
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">File</label>
                                    <input type="file" name="excel_upload" id="excel_upload" class="form-control form-control-sm">
                                </div>
                            </div>




    <div class="col-md-12" style="display: flex; justify-content:end">
        <span>
            <a href="javascript:void(0)" onclick="generateFile('excel')" type="button" style="background: #008080; color:white" class="btn btn-custon-rounded-four btn-icon">
                <i class="fa-solid fa-cloud-arrow-up"></i> Upload
            </a>
        </span>
    </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
