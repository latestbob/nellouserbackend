<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>API Reference</title>

    <link rel="stylesheet" href="/docs/css/style.css" />
    <script src="/docs/js/all.js"></script>


          <script>
        $(function() {
            setupLanguages(["bash","javascript"]);
        });
      </script>
      </head>

  <body class="">
    <a href="#" id="nav-button">
      <span>
        NAV
        <img src="/docs/images/navbar.png" />
      </span>
    </a>
    <div class="tocify-wrapper">
        <img src="/docs/images/logo.png" />
                    <div class="lang-selector">
                                  <a href="#" data-language-name="bash">bash</a>
                                  <a href="#" data-language-name="javascript">javascript</a>
                            </div>
                            <div class="search">
              <input type="text" class="search" id="input-search" placeholder="Search">
            </div>
            <ul class="search-results"></ul>
              <div id="toc">
      </div>
                    <ul class="toc-footer">
                                  <li><a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a></li>
                            </ul>
            </div>
    <div class="page-wrapper">
      <div class="dark-box"></div>
      <div class="content">
          <!-- START_INFO -->
<h1>Info</h1>
<p>Welcome to the generated API reference.
<a href="{{ route("apidoc", ["format" => ".json"]) }}">Get Postman Collection</a></p>
<!-- END_INFO -->
<h1>general</h1>
<!-- START_a925a8d22b3615f12fca79456d286859 -->
<h2>Customer login</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/auth/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"email":"ut","password":"occaecati"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/auth/login"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "email": "ut",
    "password": "occaecati"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/auth/login</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>email</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>password</code></td>
<td>string</td>
<td>required</td>
</tr>
</tbody>
</table>
<!-- END_a925a8d22b3615f12fca79456d286859 -->
<!-- START_2e1c96dcffcfe7e0eb58d6408f1d619e -->
<h2>Customer registration</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/auth/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"firstname":"tempore","lastname":"saepe","email":"veritatis","phone":"architecto","password":"delectus","dob":"facilis"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/auth/register"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "firstname": "tempore",
    "lastname": "saepe",
    "email": "veritatis",
    "phone": "architecto",
    "password": "delectus",
    "dob": "facilis"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/auth/register</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>firstname</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>lastname</code></td>
<td>string</td>
<td>optional</td>
<td>requird</td>
</tr>
<tr>
<td><code>email</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>phone</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>password</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>dob</code></td>
<td>date</td>
<td>optional</td>
<td>optional format yyyy-mm-dd</td>
</tr>
</tbody>
</table>
<!-- END_2e1c96dcffcfe7e0eb58d6408f1d619e -->
<!-- START_a5c6310c3509d478f05f37ef97fbd242 -->
<h2>Update customer profile</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/profile/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"firstname":"quam","lastname":"sit","middlename":"alias","email":"ullam","phone":"consequuntur","password":"omnis","dob":"sunt","address":"quo","state":"veritatis","city":"voluptate","religion":"assumenda","gender":"omnis","height":"vitae","weight":"ab"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/profile/update"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "firstname": "quam",
    "lastname": "sit",
    "middlename": "alias",
    "email": "ullam",
    "phone": "consequuntur",
    "password": "omnis",
    "dob": "sunt",
    "address": "quo",
    "state": "veritatis",
    "city": "voluptate",
    "religion": "assumenda",
    "gender": "omnis",
    "height": "vitae",
    "weight": "ab"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/profile/update</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>firstname</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>lastname</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>middlename</code></td>
<td>string</td>
<td>optional</td>
</tr>
<tr>
<td><code>email</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>phone</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>password</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>dob</code></td>
<td>date</td>
<td>optional</td>
<td>optional format yyyy-mm-dd</td>
</tr>
<tr>
<td><code>address</code></td>
<td>string</td>
<td>optional</td>
</tr>
<tr>
<td><code>state</code></td>
<td>string</td>
<td>optional</td>
</tr>
<tr>
<td><code>city</code></td>
<td>string</td>
<td>optional</td>
</tr>
<tr>
<td><code>religion</code></td>
<td>string</td>
<td>optional</td>
</tr>
<tr>
<td><code>gender</code></td>
<td>string</td>
<td>optional</td>
<td>optional male or female</td>
</tr>
<tr>
<td><code>height</code></td>
<td>numeric</td>
<td>optional</td>
</tr>
<tr>
<td><code>weight</code></td>
<td>numeric</td>
<td>optional</td>
</tr>
</tbody>
</table>
<!-- END_a5c6310c3509d478f05f37ef97fbd242 -->
<!-- START_b71a5a02aab383a384b96d685c0f023c -->
<h2>Upload picture</h2>
<p>Upload customer profile picture</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/profile/picture" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"picture":"dolorem"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/profile/picture"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "picture": "dolorem"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/profile/picture</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>picture</code></td>
<td>file</td>
<td>required</td>
<td>image file</td>
</tr>
</tbody>
</table>
<!-- END_b71a5a02aab383a384b96d685c0f023c -->
<!-- START_82c763c1bcc6e68dfb2a9f4407a4147c -->
<h2>Health history</h2>
<p>Fetch customer's health history data</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://206.189.236.121:3000/api/profile/health-history" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/profile/health-history"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Token not provided"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/profile/health-history</code></p>
<!-- END_82c763c1bcc6e68dfb2a9f4407a4147c -->
<!-- START_11a59c912a841991391d595f736a5a02 -->
<h2>Change password</h2>
<p>Change customer password</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/password/change" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"current_password":"vero","new_password":"ea"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/password/change"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "current_password": "vero",
    "new_password": "ea"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/password/change</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>current_password</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>new_password</code></td>
<td>string</td>
<td>required</td>
</tr>
</tbody>
</table>
<!-- END_11a59c912a841991391d595f736a5a02 -->
<!-- START_b074507f1af675135b19025749d6404d -->
<h2>Send customer message</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/contact/message" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"message":"ut"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/contact/message"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "message": "ut"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/contact/message</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>message</code></td>
<td>string</td>
<td>required</td>
</tr>
</tbody>
</table>
<!-- END_b074507f1af675135b19025749d6404d -->
<!-- START_774744abc65e28e4368f69ef4798a8f7 -->
<h2>Doctors</h2>
<p>Fetch paged list of doctors</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://206.189.236.121:3000/api/doctors" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/doctors"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Token not provided"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/doctors</code></p>
<h4>URL Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>page</code></td>
<td>optional</td>
<td>int optional defaults to 1</td>
</tr>
</tbody>
</table>
<!-- END_774744abc65e28e4368f69ef4798a8f7 -->
<!-- START_2a0648ad28005a38e1533bf9aae50c45 -->
<h2>Health tips</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://206.189.236.121:3000/api/health-tips" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/health-tips"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Token not provided"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/health-tips</code></p>
<!-- END_2a0648ad28005a38e1533bf9aae50c45 -->
<!-- START_6ce56d17034a5db188b17f9607f9699a -->
<h2>Health centers</h2>
<p>Fetch paged list of health centers</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://206.189.236.121:3000/api/health-centers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/health-centers"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Token not provided"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/health-centers</code></p>
<h4>URL Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>page</code></td>
<td>optional</td>
<td>int optional defaults to 1</td>
</tr>
</tbody>
</table>
<!-- END_6ce56d17034a5db188b17f9607f9699a -->
<!-- START_af23ab2f5d60687f1d48a54ba6071156 -->
<h2>Book appointment</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/appointments/book" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"medical_center":"dolor","reason":"modi","date":"eos","time":"expedita"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/appointments/book"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "medical_center": "dolor",
    "reason": "modi",
    "date": "eos",
    "time": "expedita"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/appointments/book</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>medical_center</code></td>
<td>uuid</td>
<td>required</td>
<td>a health center uuid</td>
</tr>
<tr>
<td><code>reason</code></td>
<td>string</td>
<td>required</td>
<td>the purpose of the appointment</td>
</tr>
<tr>
<td><code>date</code></td>
<td>date</td>
<td>optional</td>
<td>format yyyy-mm-dd</td>
</tr>
<tr>
<td><code>time</code></td>
<td>time</td>
<td>optional</td>
<td>format HH:mm</td>
</tr>
</tbody>
</table>
<!-- END_af23ab2f5d60687f1d48a54ba6071156 -->
<!-- START_5341eae881b35d79be68556d28e4a5ca -->
<h2>View Appointment</h2>
<p>View details of an appointment</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X GET \
    -G "http://206.189.236.121:3000/api/appointments/view" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/appointments/view"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "GET",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<blockquote>
<p>Example response (401):</p>
</blockquote>
<pre><code class="language-json">{
    "message": "Token not provided"
}</code></pre>
<h3>HTTP Request</h3>
<p><code>GET api/appointments/view</code></p>
<h4>URL Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>uuid</code></td>
<td>optional</td>
<td>uuid required the uuid of the appointment</td>
</tr>
</tbody>
</table>
<!-- END_5341eae881b35d79be68556d28e4a5ca -->
<!-- START_0c948b2c4b87e21dda8acfc98d983043 -->
<h2>Update appointment</h2>
<p>Update the details of an appointment</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "http://206.189.236.121:3000/api/appointments/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"uuid":"ratione","medical_center":"ut","reason":"enim","date":"atque","time":"repudiandae"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/appointments/update"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "uuid": "ratione",
    "medical_center": "ut",
    "reason": "enim",
    "date": "atque",
    "time": "repudiandae"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/appointments/update</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>uuid</code></td>
<td>uuid</td>
<td>required</td>
<td>the uuid of the appointment</td>
</tr>
<tr>
<td><code>medical_center</code></td>
<td>uuid</td>
<td>required</td>
<td>a health center uuid</td>
</tr>
<tr>
<td><code>reason</code></td>
<td>string</td>
<td>required</td>
<td>the purpose of the appointment</td>
</tr>
<tr>
<td><code>date</code></td>
<td>date</td>
<td>optional</td>
<td>format yyyy-mm-dd</td>
</tr>
<tr>
<td><code>time</code></td>
<td>time</td>
<td>optional</td>
<td>format HH:mm</td>
</tr>
</tbody>
</table>
<!-- END_0c948b2c4b87e21dda8acfc98d983043 -->
<!-- START_994d0ebdd441457bafe52e56a2e8251d -->
<h2>Cancel appointment</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "http://206.189.236.121:3000/api/appointments/cancel" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/appointments/cancel"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "PUT",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/appointments/cancel</code></p>
<h4>URL Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>uuid</code></td>
<td>optional</td>
<td>uuid required the uuid of the appointment</td>
</tr>
</tbody>
</table>
<!-- END_994d0ebdd441457bafe52e56a2e8251d -->
<!-- START_ce6c124d37580e948c6bdbac094f2705 -->
<h2>Update customer profile</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X PUT \
    "http://206.189.236.121:3000/api/nello/profile/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"firstname":"aliquam","lastname":"sed","middlename":"incidunt","email":"officia","phone":"veniam","password":"odio","dob":"dolorum","address":"et","state":"nostrum","city":"omnis","religion":"aut","gender":"enim","height":"omnis","weight":"perspiciatis"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/nello/profile/update"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "firstname": "aliquam",
    "lastname": "sed",
    "middlename": "incidunt",
    "email": "officia",
    "phone": "veniam",
    "password": "odio",
    "dob": "dolorum",
    "address": "et",
    "state": "nostrum",
    "city": "omnis",
    "religion": "aut",
    "gender": "enim",
    "height": "omnis",
    "weight": "perspiciatis"
}

fetch(url, {
    method: "PUT",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>PUT api/nello/profile/update</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>firstname</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>lastname</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>middlename</code></td>
<td>string</td>
<td>optional</td>
</tr>
<tr>
<td><code>email</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>phone</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>password</code></td>
<td>string</td>
<td>required</td>
</tr>
<tr>
<td><code>dob</code></td>
<td>date</td>
<td>optional</td>
<td>optional format yyyy-mm-dd</td>
</tr>
<tr>
<td><code>address</code></td>
<td>string</td>
<td>optional</td>
</tr>
<tr>
<td><code>state</code></td>
<td>string</td>
<td>optional</td>
</tr>
<tr>
<td><code>city</code></td>
<td>string</td>
<td>optional</td>
</tr>
<tr>
<td><code>religion</code></td>
<td>string</td>
<td>optional</td>
</tr>
<tr>
<td><code>gender</code></td>
<td>string</td>
<td>optional</td>
<td>optional male or female</td>
</tr>
<tr>
<td><code>height</code></td>
<td>numeric</td>
<td>optional</td>
</tr>
<tr>
<td><code>weight</code></td>
<td>numeric</td>
<td>optional</td>
</tr>
</tbody>
</table>
<!-- END_ce6c124d37580e948c6bdbac094f2705 -->
<!-- START_404f60051b349e142f2036e459322283 -->
<h2>Upload picture</h2>
<p>Upload customer profile picture</p>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/nello/profile/picture" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"picture":"debitis"}'
</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/nello/profile/picture"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

let body = {
    "picture": "debitis"
}

fetch(url, {
    method: "POST",
    headers: headers,
    body: body
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/nello/profile/picture</code></p>
<h4>Body Parameters</h4>
<table>
<thead>
<tr>
<th>Parameter</th>
<th>Type</th>
<th>Status</th>
<th>Description</th>
</tr>
</thead>
<tbody>
<tr>
<td><code>picture</code></td>
<td>file</td>
<td>required</td>
<td>image file</td>
</tr>
</tbody>
</table>
<!-- END_404f60051b349e142f2036e459322283 -->
<!-- START_9cd3adc2d93e72380e357a58e53eea16 -->
<h2>api/import/users</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/import/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/import/users"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/import/users</code></p>
<p><code>PUT api/import/users</code></p>
<p><code>DELETE api/import/users</code></p>
<!-- END_9cd3adc2d93e72380e357a58e53eea16 -->
<!-- START_13c25adebfbbf4054c2d1c99e12d7ada -->
<h2>api/import/health-centers</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/import/health-centers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/import/health-centers"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/import/health-centers</code></p>
<!-- END_13c25adebfbbf4054c2d1c99e12d7ada -->
<!-- START_d4e4b9e8970c4d7b6839f057fc39c48e -->
<h2>api/import/encounter</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/import/encounter" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/import/encounter"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/import/encounter</code></p>
<!-- END_d4e4b9e8970c4d7b6839f057fc39c48e -->
<!-- START_7b2effbeae57f65c5f37a728b4d199d6 -->
<h2>api/import/health-tip</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/import/health-tip" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/import/health-tip"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/import/health-tip</code></p>
<!-- END_7b2effbeae57f65c5f37a728b4d199d6 -->
<!-- START_21894a7279a96eb271bbe767f2680d63 -->
<h2>api/import/investigation</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/import/investigation" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/import/investigation"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/import/investigation</code></p>
<!-- END_21894a7279a96eb271bbe767f2680d63 -->
<!-- START_721d4db2684e133bd1ac31accef9ddd8 -->
<h2>api/import/invoice</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/import/invoice" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/import/invoice"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/import/invoice</code></p>
<!-- END_721d4db2684e133bd1ac31accef9ddd8 -->
<!-- START_753074fd64f26425567a063c8d4ec54c -->
<h2>api/import/medication</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/import/medication" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/import/medication"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/import/medication</code></p>
<!-- END_753074fd64f26425567a063c8d4ec54c -->
<!-- START_3113f2fe3db04c61f83b927f3bb67000 -->
<h2>api/import/payment</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/import/payment" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/import/payment"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/import/payment</code></p>
<!-- END_3113f2fe3db04c61f83b927f3bb67000 -->
<!-- START_05d7f9296d41661f4f22f19e63196d53 -->
<h2>api/import/procedure</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/import/procedure" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/import/procedure"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/import/procedure</code></p>
<!-- END_05d7f9296d41661f4f22f19e63196d53 -->
<!-- START_c7fd933a546ecaa6ae13ce265060245c -->
<h2>api/import/vital</h2>
<blockquote>
<p>Example request:</p>
</blockquote>
<pre><code class="language-bash">curl -X POST \
    "http://206.189.236.121:3000/api/import/vital" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"</code></pre>
<pre><code class="language-javascript">const url = new URL(
    "http://206.189.236.121:3000/api/import/vital"
);

let headers = {
    "Content-Type": "application/json",
    "Accept": "application/json",
};

fetch(url, {
    method: "POST",
    headers: headers,
})
    .then(response =&gt; response.json())
    .then(json =&gt; console.log(json));</code></pre>
<h3>HTTP Request</h3>
<p><code>POST api/import/vital</code></p>
<!-- END_c7fd933a546ecaa6ae13ce265060245c -->
      </div>
      <div class="dark-box">
                        <div class="lang-selector">
                                    <a href="#" data-language-name="bash">bash</a>
                                    <a href="#" data-language-name="javascript">javascript</a>
                              </div>
                </div>
    </div>
  </body>
</html>