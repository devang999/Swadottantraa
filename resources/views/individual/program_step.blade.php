@extends('individual.layouts.app')

@section('title')
{{ 'Programs - ' . $program->title }}
@endsection



@section('content')

<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">{{ $program->title }}</h2>
                    </div>
                </div>
            </div>
        </div>
    	<div class="content-body">
    		<div class="card">
                <div class="card-header">
                    <h4 id="cards" class="card-title">Description</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="v-program-description">{{ $program->description }}</div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-sm-12 col-lg-4">
                    <div class="card h100">
                        <div class="card-header">
                            <h4>steps</h4>
                            <i class="feather icon-more-horizontal cursor-pointer" data-toggle="collapse" data-target="#stage_wrapper"></i>
                        </div>
                        <div class="card-body py-0 collapse show" id="stage_wrapper">
                            @foreach ($steps as $step)

                                <a href="#" class="d-block border-top mt-1 pt-1">
                                    <div class="user-page-info">
                                        <h5 class="mb-0 v-stage-description">{{ $step->title }}</h5>
                                    </div>
                                    <div class="progress progress-bar-primary mb-1">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="58" aria-valuemin="58" aria-valuemax="100" style="width:58%"></div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-sm-12 col-lg-8">
                    <div class="card h100">
                        <div class="card-header">
                            <h4>{{ $current_step->title }}</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="v-program-description">{{ $current_step->description }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                @foreach ($current_step->scales as $scale)
                <div class="col-md-12">
                    <div class="card bg-transparent border-0 shadow-none collapse-icon accordion-icon-rotate">
                        <div class="card-body p-0">
                            <div class="accordion search-content-info" id="accordionExample">
                                <div class="collapse-margin search-content mt-0 bg-white">
                                    <div class="card-header" id="headingOne" role="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        <span class="lead collapse-title">
                                            {{ $scale->scale->title }}
                                        </span>
                                    </div>
                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            {{ $scale->scale->description }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

    	</div>
    </div>
</div>

@endsection
