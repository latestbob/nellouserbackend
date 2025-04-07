<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nello Payment</title>
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body>

<div id="noshow"style="display:none;" class=" show col-md-6 m-auto card rounded py-3 justify-content-center align-items-center px-3">
      <div class="text-center">
        <img src="http://asknello.com/assets/images/logo.png" alt=""style="width:20%;">
      </div>

      <h4 class="text-center">Payment Successful</h4>

      <hr>
        <br>

        <p class="alert alert-success text-center checkme"id=""style="font-size:13px;">Payment successfully, kindly close this tab to continue with the Chatbot.</p>
</div>
    
    <div id="show" class="show col-md-6 m-auto card rounded py-3 justify-content-center align-items-center px-3">
      <div class="text-center">
        <img src="http://asknello.com/assets/images/logo.png" alt=""style="width:20%;">
      </div>

        <hr>

        <h4 class="text-center">Complete Payment</h4>


        <hr>
        <br>

        <p class="alert alert-info text-center checkme"id="checkme"style="font-size:13px;">Please note that details of your appointment will be sent to <span style="font-weight:bold;" id="mail"></span> after payment is confirmed.</p>

        <br>

         <h3 id="amount" class="text-center amount"></h3>
        <form id="paymentForm">
            <!-- <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" id="email-address" required />
            </div>
            <div class="form-group">
              <label for="amount">Amount</label>
              <input type="tel" id="amount" required />
            </div>
            <div class="form-group">
              <label for="first-name">First Name</label>
              <input type="text" id="first-name" />
            </div>
            <div class="form-group">
              <label for="last-name">Last Name</label>
              <input type="text" id="last-name" />
            </div> -->
            <div class="form-submit">
                <br>
                <button type="submit" onclick="payWithPaystack()" class="btn btn-success text-center py-2">Make Payment</button>
            </div>
          </form>


        
        

    </div>
      
      <script src="https://js.paystack.co/v1/inline.js"></script> 
      <!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
      

      <script>
          console.log(window.location.href);

          let url = new URL(window.location.href);
        let params = new URLSearchParams(url.search);

        //Add a second foo parameter.
        // params.append('foo', 4);

        

        let email = params.get('email');
        let platform = params.get('platform');
        let agent_id = params.get('agent_id');
        let user_code = params.get('user_code');
        let action = params.get('action');
        let temp_id = params.get('temp_id');
        let cost = params.get('cost');
        let phone = params.get('phone');

        console.log(email);
        console.log(platform);
        console.log(agent_id);
        console.log(user_code);
        console.log(action);
        console.log(temp_id);
        console.log(cost);

         document.getElementById("amount").innerHTML = "Amount: N" + cost;      

         document.getElementById("mail").innerHTML = email;  

         if(action == "book.a.facility.visit"){
          document.getElementById("checkme").innerHTML = "Kindly Download or Print Visitation Slip after successful payment"; 
         }

const paymentForm = document.getElementById('paymentForm');
paymentForm.addEventListener("submit", payWithPaystack, false);
function payWithPaystack(e) {
  e.preventDefault();

  let handler = PaystackPop.setup({
    key: 'pk_live_c98b318b0a6960d258c3ea7ce676e804714b95eb', // Replace with your public key
    email: email,
    amount: cost * 100,
    ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
    metadata:{
        "platform":platform,
        "agent_id":agent_id,
        "user_code":user_code,
        "action":action,
        "temp_id":temp_id,
        "cost":cost,
        "method":"chat",
        "user_email":email,
        "phone":phone,
        

    },
    // label: "Optional string that replaces customer email"
    onClose: function(){
      alert('Window closed.');
    },
    callback: function(response){
      document.getElementById('show').style="display:none";
      document.getElementById('noshow').style="display:block";

      alert(message);
    }
  });

  handler.openIframe();
}

      </script>
    
</body>
</html>