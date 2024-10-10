<div class="col-md-3 col-border">
    <div class="row">
        <div class="col-md-12">
            <div class="user-profile-img text-center">
                {{-- <input type="hidden" id="student_uuid" name="student_uuid"> --}}
                @if ($imageUrl)
                    <div style="display: flex; align-items:center; flex-direction:column">
                        <div class="image-container" style=" min-width: 10rem; max-width: 20rem;">
                            <div>
                                <img style="object-fit: cover; width: 200px" name="profile_image"
                                    id="profile-image"
                                    src="{{ $imageUrl }}"
                                    alt="profile pic">
                               <i class="edit-icon fas fa-pencil-alt"> <span class="badge badge-primary"></span></i>
                            </div>

                            <div class="form" enctype="multipart/form-data">
                                @csrf
                                <input type="file" class="upload-image" name="file"
                                    style="display: none;">
                                    <div class="col-md-12 mt-4 text-center remove-profile d-none">
                                        <a href="javascript:void(0)" title="remove picture"
                                            class="btn btn-danger btn-sm"> <i class="fa fa-remove"></i> </a>
                                    </div>
                            </div>

                        </div>
                        <h6 class="mt-2">{{ $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname }}
                        </h6>
                        <p>dimensions:max-width=132,max-height=185|max:30kb</p>
                        {{-- <div class="col-md-12 mt-4 text-center remove-profile d-none">
                            <a href="javascript:void(0)" title="remove picture"
                                class="btn btn-danger btn-sm"> <i class="fa fa-remove">remove</i> </a>
                        </div> --}}
                    </div>
                @else
                    <div style="display: flex; align-items:center; flex-direction:column">
                        <div class="image-container" style=" min-width: 20rem; max-width: 20rem;">
                            <div>
                                <img style="object-fit: cover; width: 200px" name="profile_image" id="profile-image"
                                    src="{{ asset('assets/img/icon_avatar.jpeg') }}" alt="User Profile">
                                <i class="edit-icon fas fa-pencil-alt"></i>
                            </div>

                            <form enctype="multipart/form-data" class="form">
                                @csrf
                                <input type="file" class="upload-image" name="file" style="display: none;">
                            </form>

                        </div>
                        <h6 class="mt-2">{{ $student->firstname . ' ' . $student->middlename . ' ' . $student->lastname }}
                        </h6>
                    </div>
                @endif
            </div>

            <div class="mt-4 text-center">

            </div>
        </div>
        <div class="col-md-12" style="display: flex; justify-content:center">
            <div class="mt-4 text-center">
                <table>
                    <tr>
                        <th class="badge" style="margin-top: 0.5rem;">
                            <h6><i style="font-size: .8rem;" class="fa fa-circle tx-purple"></i> Class:</h6>
                        </th>
                        <th>
                            <span class="badge badge-success"> {{ $class->name }} </span>
                        </th>

                        <th></th>

                        <th class="badge" style="margin-top: 0.5rem;">
                            <h6><i style="font-size: .8rem;" class="fa fa-circle tx-info"></i> Stream:</h6>
                        </th>
                        <th>
                            <span class="badge badge-info"> {{ $stream->name }} </span>
                        </th>


                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-12 col-border-top mt-4 text-center" style="display: flex; justify-content:center">

            <div class="mt-4 text-center">
                <table>
                    <tr>
                        <th class="badge" style="margin-top: 0.5rem;">
                            <h6> <i class="fa-brands fa-uniregistry"></i> Registration Date:</h6>
                        </th>
                        <th>
                            <span class="badge badge-success">
                                {{ date('jS M, Y', strtotime($student->registration_date)) }} </span>
                        </th>
                    </tr>
                </table>
            </div>
        </div>

    </div>

</div>
