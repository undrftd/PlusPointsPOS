@extends('layout')

@section('title')
INVENTORY
@endsection

@section('css')
{{ asset('imports/css/inventory.css') }}
@endsection

@section('content')

</br>
<div class="container">
<!---title inventory-->
<h3 class="title">Inventory</h3>
</br>
<hr>
<!---end of title inventory-->
<!--second row add item button and search bar--->
<div class="row">
    <div class="col-md-8">
    <button type="button" class="btn btn-outline-info add-item-btn" data-toggle="modal" data-target=".add-item">
      Add Item
    </button>
  </div>
   <div class="col-md-4">
    <form class="form ml-auto" action="/inventory/search" method="GET">
			<div class="input-group">
    			<input class="form-control" name="product_search" type="text" placeholder="Search" aria-label="Search" style="padding-left: 20px; border-radius: 40px;" id="product-search">
    			<div class="input-group-addon" style="margin-left: -50px; z-index: 3; border-radius: 40px; background-color: transparent; border:none;">
    				<button class="btn btn-outline-info btn-sm" type="submit" style="border-radius: 100px;" id="search-btn"><i class="material-icons">search</i></button>
    			</div>
			</div>
		</form>
  </div>
 </div> 

	@if(!empty($search))
	    @if($totalcount > 7)
	    	<center><p> Showing {{$count}} out of {{$totalcount}} 
		    	@if($count > 1)
		    		{{'results'}} 
		    	@else 
		    		{{'result'}}
		    	@endif 
	    	for <b> {{ $search }} </b> </p></center>
	    @else
	    	<center><p> Showing {{$count}} 
				@if($count > 1 || $count == 0)
					{{'results'}}
				@else
					{{'result'}}
				@endif
	      	for <b> {{ $search }} </b> </p></center>
	    @endif
  @endif

    <table class="table table-hover">
    @csrf
      <thead class ="th_css">
        <tr>
		  <th scope="col">Item Name</th>
          <th scope="col">Description</th>	
		  <th scope="col">Price</th>
          <th scope="col">Stock on Hand</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody class="td_class">

      	@foreach($products as $product)
        <tr>
          <th scope="row">{{str_limit($product->product_name,40)}}</th>
          <td>{{str_limit($product->product_desc,30)}}</td>
          <td>₱ {{$product->price}}</td>
          <td>{{$product->product_qty}}</td>
          <td>
            <button type="button" id="edit-product" class="btn btn-primary edit-btn" data-toggle="modal" data-target=".edit_product" data-id="{{$product->product_id}}" data-prodname = "{{$product->product_name}}" data-proddesc ="{{$product->product_desc}}" data-prodqty="{{$product->product_qty}}" data-price="{{$product->price}}"><i class="material-icons md-18">mode_edit</i></button>
            <button type="button" id="delete-product" class="btn btn-danger del-btn" data-toggle="modal" data-target=".delete" data-id="{{$product->product_id}}"><i class="material-icons md-18">delete</i></button>
          </td>
        </tr>
        @endforeach

      </tbody>
    </table>

    {{$products->links()}}

 <!----start of modal for add item---->
    <div class="modal fade add-item" tabindex="-1" role="dialog">
     <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add New Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form>
        <div class="form-group">
        <div class="containter-fluid">
		  <div class="col-md-11 mx-auto">
          <label for="address" class="col-form-label modal-address">Item Name:</label>
          <input type="text" class="form-control modal-add" id="address">
		  </div>
  		  <div class="col-md-11 mx-auto">
          <label for="address" class="col-form-label modal-address">Description:</label>
          <input type="text" class="form-control modal-add" id="address">
		  </div>
		  <div class="col-md-11 mx-auto">
          <label for="address" class="col-form-label modal-address">Price:</label>
          <input type="text" class="form-control modal-add" id="address">
		  </div>
		  <div class="col-md-11 mx-auto">
          <label for="address" class="col-form-label modal-address">Stock:</label>
          <input type="text" class="form-control modal-add" id="address">
		  </div>



        </div>
        </div>

      </form>
        <div class="modal-footer">
          <button type="button" class="btn btn-info btn-save-modal" data-dismiss="modal">Save New Member</button>
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </div>
    </div>

    <!----end of modal---->
   <!----start of modal for EDIT---->
    <div class="modal fade edit_product" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Item</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

    <form class="nosubmitform">
    <input type="hidden" name="product_id" id="product-id-edit">
    <div class="form-group">
    <div class="containter-fluid">
		  <div class="col-md-11 mx-auto">
          <label for="prodname" class="col-form-label modal-address">Item Name:</label>
          <input type="text" name="product_name" class="form-control modal-add" id="product-name-edit">
          <p id="error-product-name-edit" class="error-edit" hidden="hidden">
		  </div>
  		  <div class="col-md-11 mx-auto">
          <label for="type" class="col-form-label modal-address">Description:</label>
          <input type="text" name="product_desc" class="form-control modal-add" id="product-desc-edit">
          <p id="error-product-desc-edit" class="error-edit" hidden="hidden">
		  </div>
		  <div class="col-md-11 mx-auto">
          <label for="price" class="col-form-label modal-address">Price:</label>
          <input type="text" name="price" class="form-control modal-add" id="product-price-edit">
          <p id="error-price-edit" class="error-edit" hidden="hidden">
		  </div>
		  <div class="col-md-11 mx-auto">
          <label for="qty" class="col-form-label modal-address">Stock on Hand:</label>
          <input type="text" name="product_qty" class="form-control modal-add" id="product-qty-edit">
          <p id="error-product-qty-edit" class="error-edit" hidden="hidden">
		  </div>



        </div>
        </div>

      </form>
        <div class="modal-footer" id="modal-footer-product-edit">
          <button type="submit" id="update-product" class="btn btn-info btn-save-modal">Save Changes</button>
          <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">Cancel</button>
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
        <center>  <p> Are you sure you want to delete this <b>item</b>?</p> </center>
        <span class="product-id-delete" hidden="hidden"></span>
        </div>

    <div class="modal-footer" id="modal-footer-product-delete">
      <button type="button" id="destroy-product" class="btn btn-info btn-save-modal">Yes</button>
      <button type="button" class="btn btn-secondary btn-close-modal" data-dismiss="modal">No</button>

    </div>
    </div>
    </div>
    </div>


    <!----end of modal---->

</div>

<script type="text/javascript">
$('.nosubmitform').submit(function(event){
  event.preventDefault();
});

//success alerts - add, update, delete
$(document).ready(function(){
if(localStorage.getItem("update"))
{
  swal({
          title: "Success!",
          text: "You have successfully updated the product!",
          icon: "success",
          button: "Close",
        });
  localStorage.clear();
}
else if(localStorage.getItem("delete"))
{
  swal({
          title: "Success!",
          text: "You have successfully deleted the product!",
          icon: "success",
          button: "Close",
        });
  localStorage.clear(); 
}
else if(localStorage.getItem("add"))
{
  swal({
          title: "Success!",
          text: "You have successfully added a product!",
          icon: "success",
          button: "Close",
        });
  localStorage.clear(); 
}
});

$('.edit_product').on('hide.bs.modal', function(){
    //hide error messages in modal
    $('#error-product-name-edit').attr("hidden", true);
    $('#error-product-desc-edit').attr("hidden", true);
    $('#error-price-edit').attr("hidden", true);
    $('#error-product-qty-edit').attr("hidden", true);

    //remove css style in modal
    $('#product-name-edit').removeAttr('style');
    $('#product-desc-edit').removeAttr('style');
    $('#product-price-edit').removeAttr('style');
    $('#product-qty-edit').removeAttr('style');
});


//edit product
$(document).on('click', '#edit-product', function() {
  $('#product-id-edit').val($(this).data('id'));
  $('#product-name-edit').val($(this).data('prodname'));
  $('#product-desc-edit').val($(this).data('proddesc'));
  $("#product-price-edit").val($(this).data('price'));
  $("#product-qty-edit").val($(this).data('prodqty'));
}); 

$('#modal-footer-product-edit').on('click', '#update-product', function(event) {
$.ajax({
  type: 'POST',
  url: '/inventory/update_product',
  data: {
          '_token': $('input[name=_token]').val(),
          'product_id': $("#product-id-edit").val(),
          'product_name': $("#product-name-edit").val(),
          'product_desc': $("#product-desc-edit").val(),
          'price': $("#product-price-edit").val(),
          'product_qty': $("#product-qty-edit").val()
        },
  success: function(data) {
    console.log(data);
    if ((data.errors)) {
        if(data.errors.product_name)
        {
          $('#error-product-name-edit').removeAttr("hidden");
          $('#error-product-name-edit').text(data.errors.product_name);
          $('#product-name-edit').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-product-name-edit').attr("hidden", true);
          $('#product-name-edit').removeAttr('style');
        }

        if(data.errors.product_desc)
        {
          $('#error-product-desc-edit').removeAttr("hidden");
          $('#error-product-desc-edit').text(data.errors.product_desc);
          $('#product-desc-edit').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-product-desc-edit').attr("hidden", true);
          $('#product-desc-edit').removeAttr('style');
        }

        if(data.errors.price)
        {
          $('#error-price-edit').removeAttr("hidden");
          $('#error-price-edit').text(data.errors.price);
          $('#product-price-edit').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-price-edit').attr("hidden", true);
          $('#product-price-edit').removeAttr('style');
        }

        if(data.errors.product_qty)
        {
          $('#error-product-qty-edit').removeAttr("hidden");
          $('#error-product-qty-edit').text(data.errors.product_qty);
          $('#product-qty-edit').css("border", "1px solid #cc0000");
        }
        else
        {
          $('#error-product-qty-edit').attr("hidden", true);
          $('#product-qty-edit').removeAttr('style');
        }
    }
    else
    {
      localStorage.setItem("update","success");
      window.location.reload();   
    }
    },

      error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
      console.log(JSON.stringify(jqXHR));
      console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
      }
  });
});

//delete product
$(document).on('click', '#delete-product', function() {
  $('.product-id-delete').text($(this).data('id'));
});

$('#modal-footer-product-delete').on('click', '#destroy-product', function(){
$.ajax({
  type: 'POST',
  url: '/inventory/delete_product',
  data: {
    '_token': $('input[name=_token]').val(),
    'product_id': $('.product-id-delete').text()
  },
  success: function(data){
    localStorage.setItem("delete","success");
    window.location.reload();
  },
  error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
    console.log(JSON.stringify(jqXHR));
    console.log("AJAX error: " + textStatus + ' : ' + errorThrown);
  }
  });
});
</script>

@endsection