@extends('core/base::layouts.master')

@section('content')
<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-primary text-white">
            <div class="d-flex align-items-center">
                <div class="flex-fill">
                    <h4 class="mb-0">54,390</h4>
                    total comments
                </div>

                <i class="ph-chats ph-2x opacity-75 ms-3"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-danger text-white">
            <div class="d-flex align-items-center">
                <div class="flex-fill">
                    <h4 class="mb-0">389,438</h4>
                    total orders
                </div>

                <i class="ph-package ph-2x opacity-75 ms-3"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-success text-white">
            <div class="d-flex align-items-center">
                <i class="ph-hand-pointing ph-2x opacity-75 me-3"></i>

                <div class="flex-fill text-end">
                    <h4 class="mb-0">652,549</h4>
                    total clicks
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card card-body bg-indigo text-white">
            <div class="d-flex align-items-center">
                <i class="ph-users-three ph-2x opacity-75 me-3"></i>

                <div class="flex-fill text-end">
                    <h4 class="mb-0">245,382</h4>
                    total visits
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
