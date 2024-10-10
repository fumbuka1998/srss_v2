@extends('layout.index')



@section('body')

<style>
            .background-row {
            background: linear-gradient(rgba(255, 255, 255, 0.1), rgba(255, 255, 255, 0.3)), url('{{ asset("assets/icons/img_3.jpg") }}') fixed;
            padding: 20px; /* Adjust the padding as needed */
            text-align: center;
            font-family: 'Arial', sans-serif;
        }

        .overlay-text {
            color: #333;
        }

        h1 {
            font-size: 4em;
            margin-bottom: 10px;
            color: #ffffff;

        }

        p {
            font-size: 1.2em;
            color: #ffffff;
        }


        .review-comparisons {
    text-align: center;
    /* margin: 20px; */
}

.gray-button {
    display: inline-block;
    padding-left: 2rem;
    padding-right: 2rem;
    text-decoration: none;
    background-color: #ccc;
    color: #333;
    font-weight: bold;
    border-radius: 5px;
}

.gray-button:hover {
    background-color: #999;
}

/* Style for the "Versus" icon */
.head-icon {
    width: 30px;
    height: 35px;
    display: inline-block;
    vertical-align: middle;
    margin-right: 5px;
    /* Replace the content property with your actual icon */
    background: url('{{ asset('assets/icons/versus.png')  }}') no-repeat;
    background-size: cover;
}

#compareButton {
    display: inline-block;
    padding: 10px 20px;
    background-color: #da0001; /* Button background color */
    color: #fff; /* Button text color */
    text-decoration: none;
    font-weight: bold;
    border-radius: 5px;
    transition: background-color 0.3s ease; /* Add a smooth transition effect */
}

#compareButton:hover {
    background-color: #0056b3; /* Change the background color on hover */
}

.the-arena{

display: flex;
margin-top: 4rem;
justify-content: space-between;

}

.the-arena select {
    width: 100%;
    margin-bottom: 10px;
}

.the-arena input {
    width: 100%;
    margin-bottom: 10px;
}

.comparison-table {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .side {
            width: 48%; /* Adjust as needed */
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            box-sizing: border-box;
            overflow-x: auto;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-bottom: 20px;
            margin-top: 2rem;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* .right-align {
            text-align: right;
        } */
        table th{
            background:#069613;
            color: white;
        }
        .bold{
            font-weight: bold;

        }

        @media only screen and (max-width: 600px) {
            .side {
                width: 100%;
            }
        }



</style>


<div class="row">
    <div class="col-md-12 background-row">
        <div class="overlay-text">
            <h1>Subjects Arena</h1>
            <p>Data </p>
        </div>
    </div>
</div>

<div class="the-arena">

        <div class="row">
            {{-- <div class="col-md-1"></div> --}}
        <div class="col-md-4">
            <div class="form-group">
                <select name="" id="" class="form-control">
                    <option value="">Select Year...</option>
                    @foreach ($years as $year )
                    <option value="{{ $year->id }}">{{  $year->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <select name="left_class"  class="form-control class">
                    <option value="">Select Class...</option>
                    @foreach ($classes as $class )
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>


        <div class="col-md-4">
                    <div class="form-group">
                        <select name="left_stream" id="stream_id" class="form-control stream">
                            <option value="">Select Stream...</option>
                        </select>
                    </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <select name="" id="" class="form-control">
                    <option value="">Select Subject...</option>
                </select>
            </div>
        </div>

        </div>

 <div>
        <div class="col-md-1">
            <div class="review-comparisons">
                <a class="gray-button" data-columns="12" href="#">
                    <i class="head-icon icon-compare-arrows"></i>Versus
                </a>
            </div>

        </div>
    </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <select name="right_year" class="form-control">
                        <option value="">Select Year...</option>
                        @foreach ($years as $year )
                    <option value="{{ $year->id }}">{{  $year->name }}</option>
                    @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <select name="right_class" class="form-control class">
                        <option value="">Select Class...</option>
                        @foreach ($classes as $class )
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <select name="right_stream"  class="form-control stream">
                        <option value="">Select Stream...</option>
                    </select>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <select name="right_subject" class="form-control">
                        <option value="">Select Subject...</option>
                    </select>
                </div>
            </div>
        </div>
</div>
<div style="margin-bottom: 2rem">
    <table>
        <tr>
            <th>Category</th>
            <th>A</th>
            <th>B</th>
        </tr>
        <tr>
            <td class="bold">AVERAGE</td>
            <td>44</td>
            <td class="right-align">19</td>
        </tr>
        <tr>
            <td class="bold">GRADE</td>
            <td>12</td>
            <td class="right-align">70</td>
        </tr>
        <tr>
            <td class="bold">No. of <span style="color: #069613">A</span>'s</td>
            <td>100</td>
            <td class="right-align">33</td>
        </tr>

        <tr>
            <td class="bold">No. of   <span style="color:#2876dd ">B</span>'s</td>
            <td>8</td>
            <td class="right-align">7</td>
        </tr>
        <tr>
            <td class="bold">No. of <span style="color:#7450f8 ">C</span>'s</td>
            <td>89</td>
            <td class="right-align">20</td>
        </tr>
        <tr>
            <td class="bold">No. of <span style="color: #f9a11c">D</span>'s</td>
            <td>12</td>
            <td class="right-align">80</td>
        </tr>
        <tr>
            <td class="bold">No. of <span style="color: #da0001">F</span>'s</td>
            <td>67</td>
            <td class="right-align">22</td>
        </tr>
        <tr>
            <td class="bold">Students Sat</td>
            <td>45</td>
            <td class="right-align">31</td>
        </tr>
        <tr>
            <td class="bold">Passed</td>
            <td>66</td>
            <td class="right-align">76</td>
        </tr>

        <tr>
            <td class="bold">Failed</td>
            <td>22</td>
            <td class="right-align">12</td>
        </tr>

        <!-- Add more rows for other specifications -->
    </table>
</div>

<div class="custom-card" style="margin-bottom: 6rem; margin-top: 3rem">

    <div class="card-body">

        <div  class="row">

            <div class="col-md-7">
                <div class="chart-container" style="position: relative; height:40vh; width:100vw">
                    <canvas id="myChart"></canvas>
                </div>
            </div>

            <div class="col-md-5">
                <div class="chart-container" style="position: relative; height:40vh; width:100vw">
                    <canvas id="barChat"></canvas>
                </div>
            </div>

        </div>

    </div>



</div>

{{--
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

      <script>

      </script> --}}



@section('scripts')

<script>



/* CHARTS */
pieChart();
function pieChart(){

const ctx = document.getElementById('barChat');

new Chart(ctx, {
  type: 'pie',
  data: {
    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
    datasets: [{
      label: '# of Votes',
      data: [12, 19, 3, 5, 2, 3],
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

}


barChart();

function barChart(){

const ctx = document.getElementById('myChart');

new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
    datasets: [{
      label: '# of Votes',
      data: [12, 19, 3, 5, 2, 3],
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  }
});

}



/* END */




$('.class').change(function(){
let el = $(this);
let id  = $(this).val();


$.ajax({
    url:'{{ route('academic.class.streams.fetch') }}',
    method:"POST",
    data:{
    id:id
},
beforeSend: function(xhr) {
    showLoader();
    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
},

success:function(res){
    hideLoader();
    el.parent().parent().parent().find('.stream').html(res)
    // $('#stream_id').html(res);

},

error:function(res){


    console.log(res)

}

})



});



</script>
@endsection
@endsection







