@extends('admin.layouts.app')
@section('title', 'Question Create')

@section('content')
    @include('admin.includes._breadcrumb', ['title' => 'Create Question', 'items' => ['Home' => route('admin.dashboard'), 'companies' => route('admin.companies.list'), 'Create Question' => null]])

    <style>
        .question {
            margin-bottom: 20px;
        }

        .option {
            margin-bottom: 10px;
        }
    </style>
    @php
        $options = ["Option 1","Option 2", "Option 3","Option 4"];
    @endphp
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <form id="form" action="{{route("admin.companies.save")}}" method="POST"
                          enctype="multipart/form-data"
                          data-parsley-validate>
                        @csrf

                        @isset($question)
                            <input type="hidden" name="id" value="{{ $question->id }}">
                        @endisset

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="image" class="form-label required">Company Logo <span
                                        data-bs-title="image is required for the questions, So, Please attache valid image"
                                        data-bs-toggle="tooltip"><i
                                            class="fa-solid fa-info-circle"></i></span></label>
                                <div class="placeholder-image"
                                     style="width: 160px; height: 80px">
                                    <img
                                        id="image-img"
                                        src="{{ isset($question->company_logo) ? Storage::url($question->company_logo) : "" }}"
                                        alt="image">

                                    <div class="placeholder-overlay"
                                         onclick="$('#imageImg').trigger('click');">
                                        <i class="ri ri-camera-fill ri-2x text-white"></i>
                                    </div>

                                </div>
                                <input class="d-none" name="logo" accept="image/*"
                                       type='file'
                                       id="imageImg"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="name">Company Name:</label>
                                <input type="text" name="name" id="name" class="form-control">
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary my-2">Submit Company</button>
                    </form>

                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->


        <!-- end col -->
    </div>
@endsection

@push('scripts')
    <script type="module">

        $(document).ready(function () {
            $('.js-example-basic-single').select2();
        });


        $('#imageImg').change(function (event) {
            document.getElementById("image-img").src = URL.createObjectURL(event.target.files[0])
        })

    </script>
@endpush
