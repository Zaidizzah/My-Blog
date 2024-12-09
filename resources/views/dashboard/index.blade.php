@extends('layouts.main')

@section('container')
    
    <!-- Konten Dashboard -->
    <section class="dashboard-section my-3" aria-label="dashboard section">
        <div class="row">
            <div class="col-md-3">
                <div class="card border-dark rounded-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon" style="font-size: 36px">
                                <i class="fa fa-user-cog fa-lg"></i>
                            </div>
                            <div class="text-end">
                                <h3>{{ $user->role }}</h3>
                                <p class="mb-0">Your Roles</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-dark rounded-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon" style="font-size: 36px">
                                <i class="fa fa-newspaper fa-lg"></i>
                            </div>
                            <div class="text-end">
                                <h3>{{ $count_posts }}</h3>
                                <p class="mb-0">Posts Uploaded</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-dark rounded-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon" style="font-size: 36px">
                                <i class="fa fa-list fa-lg"></i>
                            </div>
                            <div class="text-end">
                                <h3>{{ $count_categories }}</h3>
                                <p class="mb-0">All Categories</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-dark rounded-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="icon" style="font-size: 36px">
                                <i class="fa fa-chart-line fa-lg"></i>
                            </div>
                            <div class="text-end">
                                <h3>92%</h3>
                                <p class="mb-0">Success Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Echarts - Calender heatmap -->
        <div class="heatmap-section chart-container bg-light border border-1 border-dark mt-3" aria-label="heatmap section - recording your activity">
            
        </div>
    </section>

@endsection