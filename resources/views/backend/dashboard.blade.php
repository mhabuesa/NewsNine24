@extends('backend.layouts.app')
@section('title', 'Dashboard')
@push('style')
    <style>
        .modal-dialog {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            /* viewport er height */
        }
    </style>
@endpush
@section('content')

    <div class="content">
        <div
            class="d-flex flex-column flex-md-row justify-content-md-between align-items-md-center py-2 text-center text-md-start">
            <div class="flex-grow-1 mb-1 mb-md-0">
                <h1 class="h3 fw-bold mb-2">
                    Dashboard
                </h1>
                <h2 class="h6 fw-medium fw-medium text-muted mb-0">
                    Welcome <a class="fw-semibold" href="{{ route('profile.index') }}">{{ auth()->user()->name }}</a>,
                    everything looks great.
                </h2>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row items-push justify-content-center">
            <!-- Total Contribution -->
            <div class="col-sm-6 col-xxl-3 my-2">
                <a href="#" class="text-black">
                    <div class="block block-rounded d-flex flex-column h-100 mb-0">
                        <div
                            class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="fs-3 fw-bold">00</dt>
                                <dd class="fs-sm fw-medium text-muted mb-0">Total Contribution</dd>
                            </dl>
                            <div class="item item-rounded-lg bg-body-light">
                                <i class="fas fa-hand-holding-usd fs-3 text-success"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Active Investments -->
            <div class="col-sm-6 col-xxl-3 my-2">
                <a href="#" class="text-black">
                    <div class="block block-rounded d-flex flex-column h-100 mb-0">
                        <div
                            class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="fs-3 fw-bold">00</dt>
                                <dd class="fs-sm fw-medium text-muted mb-0">Active Investments</dd>
                            </dl>
                            <div class="item item-rounded-lg bg-body-light">
                                <i class="fas fa-briefcase fs-3 text-info"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Invested Amount -->
            <div class="col-sm-6 col-xxl-3 my-2">
                <a href="#" class="text-black">
                    <div class="block block-rounded d-flex flex-column h-100 mb-0">
                        <div
                            class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="fs-3 fw-bold text-warning">00</dt>
                                <dd class="fs-sm fw-medium mb-0 text-warning">Invested Amount</dd>
                            </dl>
                            <div class="item item-rounded-lg bg-body-light">
                                <i class="fas fa-box-archive fs-3 text-info"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Total Profit -->
            <div class="col-sm-6 col-xxl-3 my-2">
                <a href="#" class="text-black">
                    <div class="block block-rounded d-flex flex-column h-100 mb-0">
                        <div
                            class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="fs-3 fw-bold">00</dt>
                                <dd class="fs-sm fw-medium text-muted mb-0">Total Profit</dd>
                            </dl>
                            <div class="item item-rounded-lg bg-body-light">
                                <i class="fas fa-chart-line fs-3 text-primary"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Reserved Fund -->
            <div class="col-sm-6 col-xxl-3 my-2">
                <a href="#" class="text-black">
                    <div class="block block-rounded d-flex flex-column h-100 mb-0">
                        <div
                            class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="fs-3 fw-bold">00</dt>
                                <dd class="fs-sm fw-medium text-muted mb-0">Reserved Fund</dd>
                            </dl>
                            <div class="item item-rounded-lg bg-body-light">
                                <i class="fas fa-university fs-3 text-warning"></i>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Net Balance -->
            <div class="col-sm-6 col-xxl-3 my-2">
                <div class="block block-rounded d-flex flex-column h-100 mb-0">
                    <div
                        class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">
                                00
                            </dt>
                            <dd class="fs-sm fw-medium text-muted mb-0">Net Balance</dd>
                        </dl>
                        <div class="item item-rounded-lg bg-body-light">
                            <i class="fas fa-balance-scale fs-3 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Members -->
            <div class="col-sm-6 col-xxl-3 my-2">
                <a href="#" class="text-black">
                    <div class="block block-rounded d-flex flex-column h-100 mb-0">
                        <div
                            class="block-content block-content-full flex-grow-1 d-flex justify-content-between align-items-center">
                            <dl class="mb-0">
                                <dt class="fs-3 fw-bold">00</dt>
                                <dd class="fs-sm fw-medium text-muted mb-0">Total Members</dd>
                            </dl>
                            <dl class="mb-0">
                                <dt class="fs-3 fw-bold">00</dt>
                                <dd class="fs-sm fw-medium text-muted mb-0">Total Share</dd>
                            </dl>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="block block-rounded mt-3">
            <div class="block-header block-header-default">
                <h3 class="block-title">Contributions</h3>
                <div class="block-options space-x-1">
                    <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle"
                        data-target="#one-dashboard-search-orders" data-class="d-none">
                        <i class="fa fa-search"></i>
                    </button>
                    <a href="#" class="btn btn-sm btn-primary"> Details</a>
                </div>
            </div>
            <div id="one-dashboard-search-orders" class="block-content border-bottom d-none">
                <div class="push">
                    <div class="input-group">
                        <input type="text" class="form-control form-control-alt" id="contributeSearch" name="search"
                            placeholder="Search all Contributes..">
                    </div>
                </div>
            </div>
            <div class="block-content block-content-full">
                <div class="table-responsive-sm">
                    <table class="table table-striped table-vcenter" id="contributionsTable">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Cycle</th>
                                <th>Amount</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="block-content block-content-full bg-body-light p-2">
                <div class="text-center">
                    <button id="loadMore" class="btn btn-primary">
                        <span class="btn-text">Load More</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('footer_scripts')
    <script>
        $(document).ready(function() {
            $("#contributeSearch").on("keyup", function() {
                let value = $(this).val().toLowerCase();

                $("#contributionsTable tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var modalEl = document.getElementById('modal-block-fromright');

            // শুধু তখনই auto show হবে যদি .visible class থাকে
            if (modalEl.classList.contains('visible')) {
                setTimeout(function() {
                    var modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }, 2000);
            }
        });
    </script>
@endpush
