<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OWC Appointment Payment</title>
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
</head>
<body>

<div id="noshow"style="display:none;" class=" show col-md-6 m-auto card rounded py-3 justify-content-center align-items-center px-3">
      <div class="text-center">
        <img src="https://owcappointment.com/static/media/mainlogo.97dcb8ed6a9966819480.png" alt=""style="width: 180px;">
      </div>

      <h4 class="text-center">Payment Successful</h4>

      <hr>
        <br>

        <p class="alert alert-success text-center checkme"id=""style="font-size:13px;">Payment successfully, kindly close this tab to continue with the Chatbot.</p>
</div>
    
    <div id="show" class="show col-md-6 m-auto card rounded py-3 justify-content-center align-items-center px-3">
      <div class="text-center">
      <img src="https://owcappointment.com/static/media/mainlogo.97dcb8ed6a9966819480.png" alt=""style="width: 180px;">
      </div>

        <hr>

        <h4 class="text-center">Complete Payment</h4>


        <hr>
        <br>

        <p class="alert alert-info text-center checkme"id="checkme"style="font-size:13px;">Please note that details of your appointment will be sent to <span style="font-weight:bold;" id="mail"></span> after payment is confirmed.</p>

        <br>

         <h3 id="amount" class="text-center amount"></h3>
        <form id="paymentForm">
            
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
      
        let cost = params.get('cost');

        let type = params.get('type');
        let doctor_email = params.get('doctor_email');
        let user_email = params.get('user_email');
        let date = params.get('date');
        let time = params.get('time');
        let spec = params.get('spec');
        

       
        console.log(platform);
        console.log(agent_id);
        console.log(user_code);
        console.log(action);
        console.log(type);
        console.log(doctor_email);
        console.log(user_email);
        console.log(date);
        console.log(time);
        console.log(spec);

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
    email: user_email,
    amount: cost * 100,
    ref: ''+Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
    metadata:{
        "platform":platform,
        "agent_id":agent_id,
        "user_code":user_code,
        "action":action,
       
        "cost":cost,
        "method":"chat",
        "type":type,
        "doctor_email":doctor_email,
        "date":date,
        "time":time,
        "spec":spec,
        "user_email":user_email



        

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