<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>AskNello - Quick Checkout</title>
  </head>
  <body>
    

            <!-- Image and text -->
<nav class="navbar navbar-light bg-light">
  <a class="navbar-brand" href="https://asknello.com">
  <img src="http://asknello.com/assets/images/logo.png" alt=""style="width:100px;">
  
  </a>
</nav>

   <Br>
   <BR>

    <div class="card rounded py-2 col-md-8 m-auto">

    @if(session('error'))
      
      <div class="alert alert-danger text-center">
          <p class="fontWeight-bold">{{session('error')}}</p>
      </div>

    @endif
        <h4 class="text-center py-2">AskNello Quick Checkout</h4>

        <p class="text-center">Kindly make payment for the below cart items</p>


        @foreach($cart as $items)

        <div class="cartitems row my-2">
            <div class="col-5">
            <img src="{{ $items->drug->image }}"class="img-thumbnail" width="100"/>
                
            </div>

            <div class="col-7">
                <h6>{{$items->drug->name}}</h6>
                <p><span>Brand - {{$items->drug->brand}}  </span> |  <span>Quantity - {{$items->quantity}}  </span> </p>
                <p><span>Unit Price - ₦{{$items->drug->price}}  </span> </p>
            </div>
        </div>

        <hr>




        @endforeach


        <div class="rounded text-center">
            <H5>Total Price -  ₦{{$totalprice}}</H5>
        </div>

        @if(\App\Models\Order::where("cart_uuid",$items->cart_uuid)->exists())

          <div class="alert alert-info text-center py-3">
            <p class="text-center font-weight-bold">Order have already been paid for</p>
          </div>


        @else

        <!-- <form action="/cart/pay" method="POST"> 
          @csrf
            <input type="text" name="user_email"placeholder="Enter Registered Email Address"class="form-control"required> 
            <input type="text" name="coupon"placeholder="Enter Valid Coupon (10% Discount)"class="form-control"> 
            <input type="hidden" name="amount" value="{{$totalprice * 100}}"> 
            <input type="hidden" name="cartid" value="{{$items->cart_uuid}}"> 
            <button class="w-100 py-3 btn mt-3"style="background:#1997cf;fontWeight:bold;color:white;" type="submit" name="pay_now" id="pay-now" title="Pay now">PROCEED TO CHECKOUT</button>
            </form> -->

      <hr>

            <form action="/cart/pay" method="POST">
              @csrf
                <div class="form-row mt-2">
                  <div class="form-group col-md-6">
                    <label for="inputEmail4">Email Address <span class="text-danger px-1">*</span></label>
                    <input type="email"name="user_email" class="form-control" id="inputEmail4" placeholder="Enter Registered Email Address"required>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="inputPassword4">Coupon</label>
                    <input type="text"name="coupon" class="form-control" id="inputPassword4" placeholder="Enter Coupon Code">
                  </div>
                </div>
                <input type="hidden" name="amount" value="{{$totalprice * 100}}"> 
                <input type="hidden" name="cartid" value="{{$items->cart_uuid}}"> 
              

                <button class="w-100 py-3 btn mt-3"style="background:#1997cf;fontWeight:bold;color:white;" type="submit" name="pay_now" id="pay-now" title="Pay now">PROCEED TO CHECKOUT</button>
              </form>
                    


        @endif



        <!-- <div class="w-100 py-3 btn mt-3"style="background:#1997cf;fontWeight:bold;color:white;">PROCEED TO CHECKOUT</div> -->

       
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>