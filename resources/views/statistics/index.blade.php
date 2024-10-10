
@extends('layout.index')

@section('body')

<style>
    .select2-container {
        min-width: 27rem;
    }
    .chosen-select-single{
        display: flex;
        flex-direction: column;
    }
</style>


<div class="charts-area mg-b-15">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">
                <div class="charts-single-pro shadow-reset nt-mg-b-30">
                    <div class="alert-title">
                        <h2>Basic Line Chart</h2>
                        <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                    </div>
                    <div id="basic-chart">
                        <canvas id="basiclinechart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="charts-single-pro shadow-reset nt-mg-b-30">
                    <div class="alert-title">
                        <h2>Line Chart Multi Axis</h2>
                        <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                    </div>
                    <div id="axis-chart">
                        <canvas id="linechartmultiaxis"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="charts-single-pro shadow-reset nt-mg-b-30">
                    <div class="alert-title">
                        <h2>Line Chart Stepped</h2>
                        <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                    </div>
                    <div id="stepped-chart">
                        <canvas id="linechartstepped"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="charts-single-pro shadow-reset nt-mg-b-30">
                    <div class="alert-title">
                        <h2>Line Chart Interpolation</h2>
                        <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                    </div>
                    <div id="polation-chart">
                        <canvas id="linechartinterpolation"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="charts-single-pro shadow-reset nt-mg-b-30">
                    <div class="alert-title">
                        <h2>Chart Line styles</h2>
                        <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                    </div>
                    <div id="styles-chart">
                        <canvas id="linechartstyles"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="charts-single-pro shadow-reset nt-mg-b-30">
                    <div class="alert-title">
                        <h2>Chart Line point circle</h2>
                        <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                    </div>
                    <div id="circle-chart">
                        <canvas id="linechartpointcircle"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="charts-single-pro ln-ch-mg-b shadow-reset">
                    <div class="alert-title">
                        <h2>Chart Line Point rectRot</h2>
                        <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                    </div>
                    <div id="rectRot-chart">
                        <canvas id="linechartpointrectRot"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="charts-single-pro shadow-reset">
                    <div class="alert-title">
                        <h2>Chart Line point cross</h2>
                        <p>A bar chart provides a way of showing data values. It is sometimes used to show trend data. we create a bar chart for a single dataset and render that in our page.</p>
                    </div>
                    <div id="cross-chart">
                        <canvas id="linechartpointcross"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
    </div>

@section('scripts')

<script>



</script>


@endsection
@endsection
























