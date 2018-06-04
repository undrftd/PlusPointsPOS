@extends('layout')

@section('title')
RELOAD SALES
@endsection

@section('css')
{{ asset('imports/css/sales.css') }}
@endsection

@section('content')

</br>
<div class="container">
<!---title inventory-->
<h3 class="title">Reload Sales Record</h3>
</br>
<hr>
<!---end of title inventory-->
<!--second row add item button and search bar--->
<div class="row">
    <div class="col-md-8">
   <h3 class="text-info">Total Sales: <span style="color:dimgray">P100</span></h3>
  </div>
   <div class="col-md-4">
    <form class="form ml-auto" action="/logs/reload/filter" method="GET">
      <div class="input-group">
          <input class="form-control" name="date_filter" type="text" placeholder="Filter by Date" aria-label="Search" style="padding-left: 20px; border-radius: 40px;" id="date_filter" autocomplete="off">
          <div class="input-group-addon" style="margin-left: -50px; z-index: 3; border-radius: 40px; background-color: transparent; border:none;">
            <button class="btn btn-outline-info btn-sm" type="submit" style="border-radius: 100px;" id="search-btn"><i class="material-icons">search</i></button>
          </div>
      </div>
    </form>
  </div>
 </div> <!----end of second row--->
 <!---table start---->

  @if((!empty($date_start) && !empty($date_end)) && ($date_start != $date_end))
      @if($totalcount > 7)
        <center><p> Showing {{$count}} out of {{$totalcount}} results
          from <b>{{date('F d, Y', strtotime($date_start))}}</b> until <b>{{date('F d, Y', strtotime($date_end))}} </b> </p></center>
      @else
        <center><p> Showing {{$count}}
        @if($count > 1 || $count == 0)
          {{'results'}}
        @else
          {{'result'}}
        @endif
          from <b>{{date('F d, Y', strtotime($date_start))}}</b> until <b>{{date('F d, Y', strtotime($date_end))}} </b> </p></center>
      @endif
  @elseif((!empty($date_start) && !empty($date_end)) && ($date_start == $date_end))
      @if($totalcount > 7)
        <center><p> Showing {{$count}} out of {{$totalcount}} results
          for <b>{{date('F d, Y', strtotime($date_start))}}</b> </p></center>
      @else
        <center><p> Showing {{$count}}
        @if($count > 1 || $count == 0)
          {{'results'}}
        @else
          {{'result'}}
        @endif
          for <b>{{date('F d, Y', strtotime($date_start))}}</b> </p></center>
      @endif  
  @endif

    <table class="table table-hover">
      @csrf
      <thead class ="th_css">
        <tr>
          <th scope="col">Date</th>
          <th scope="col">Card Number</th>
          <th scope="col">Member Name</th>
          <th scope="col">Amount Reloaded</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody class="td_class">
        @foreach($reloads as $reload)
        <tr>
          <th scope="row">{{date("F d, Y", strtotime($reload->transaction_date))}}</th>
          <td>{{$reload->user->card_number}}</td>
          <td>{{$reload->user->firstname . " " . $reload->user->lastname}}</td>
          <td>₱ {{number_format($reload->amount_due,2, '.', '')}}</td>

          <td> <button type="button" id="view-receipt" data-id="{{$reload->id}}" class="btn btn-secondary edit-btn" data-toggle="modal" data-target=".view_details"><i class="material-icons md-18">receipt</i></button>
      <button type="button" class="btn btn-danger del-btn" data-toggle="modal" data-target=".delete"><i class="material-icons md-18">delete</i></button> </td>
        </tr>
        @endforeach

      </tbody>
    </table>

    {{$reloads->links()}}


   <!----start of modal for view---->
    <div class="modal fade view_details" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">View Receipt</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

         <div class="modal-body" id="view_body">
         </div>
      </div>
    </div>
    </div>
    <!----end of modal---->
   <!----start of modal for DELETE---->
    <div class="modal fade delete" tabindex="-1" role="dialog">
    <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Message</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
        <center>  <p> Are you sure you want to delete this <b>log</b>?</p> </center>
        </div>

    <div class="modal-footer">
      <button type="button" class="btn btn-info btn-save-modal" data-dismiss="modal">Yes</button>
      <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">No</button>

    </div>
    </div>
    </div>
    </div>

</div>

<script type="text/javascript">
  $(document).on('click', '#view-receipt', function() {
    $.ajax({
    type: 'POST',
    url: '/logs/reload/showdetails',
    data: {
      '_token': $('input[name=_token]').val(),
      'reload_id': $(this).data('id'),
    },
    success: function(data){
      $('.view_details').modal('show');
      $('#view_body').html(data);
    },
    error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log(JSON.stringify(jqXHR));
      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
    }
    });
  });

  $('input[name="date_filter"]').daterangepicker({
    autoUpdateInput: false,
    opens: 'center',
    locale: {
        cancelLabel: 'Clear'
    }
  });

  $('input[name="date_filter"]').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
  });

  $('input[name="date_filter"]').on('cancel.daterangepicker', function(ev, picker) {
      $(this).val('');
  });
</script>

@endsection