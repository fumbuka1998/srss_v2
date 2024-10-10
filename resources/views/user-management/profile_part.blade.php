<div class="col-md-3 col-border">
    <div class="row">
        <div class="col-md-12">
            <div class="user-profile-img text-center">
                <input type="hidden" id="student_uuid" name="student_uuid" value="{{ $user->uuid }}">
                @if ($imageUrl)
                <div style="display: flex; align-items:center;">
                <div class="image-container" style=" min-width: 10rem; max-width: 20rem;">
                    <div>
                        <img style="width: 200px;" src="{{ $imageUrl }}" alt="profile pic">
                       <span class="badge badge-primary"><i  class="fas fa-pencil-alt edit-icon"></i></span>
                    </div>

                    <form enctype="multipart/form-data">
                        @csrf
                        <input type="file" class="upload-image" name="file" style="display: none;">
                    </form>

                </div>
                </div>

                @else

                <div style="display: flex; align-items:center; flex-direction:column">
                    <div class="image-container" style=" min-width: 20rem; max-width: 20rem;">
                        <div>
                            <img style="object-fit: cover; width: 200px" name="profile_image" id="profile-image" src="{{ asset('assets/img/icon_avatar.jpeg') }}" alt="User Profile">
                            <i class="edit-icon fas fa-pencil-alt"></i>
                        </div>

                        <form enctype="multipart/form-data" class="form">
                            @csrf
                            <input type="file" class="upload-image" name="file" style="display: none;">
                        </form>

                    </div>
                    <h6 class="mt-2">{{ strtoupper($user->firstname.' '.$user->middlename.' '.$user->lastname )   }}</h6>
                </div>
                @endif
            </div>

            {{-- <div class="mt-4 text-center">

            </div> --}}


            <div class="mt-4 text-center">
                <table>
                    <tr>
                        <th class="badge" style="margin-top: 0.5rem;"> <h6><i style="font-size: .8rem;" class="fa fa-circle tx-red"></i> Roles:</h6> </th>
                        @foreach ( $roles as $key => $userHasrole )
                        <th>
                        <span class="badge badge-info"> {{ $userHasrole->roles->name }} </span>
                        </th>
                        @endforeach

                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-12 col-border-top mt-4 text-center">

        </div>

    </div>

</div>
