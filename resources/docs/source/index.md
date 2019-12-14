---
title: API Reference

language_tabs:
- bash
- javascript

includes:

search: true

toc_footers:
- <a href='http://github.com/mpociot/documentarian'>Documentation Powered by Documentarian</a>
---
<!-- START_INFO -->
# Info

Welcome to the generated API reference.
[Get Postman Collection](http://206.189.236.121:3000/docs/collection.json)

<!-- END_INFO -->

#general


<!-- START_a925a8d22b3615f12fca79456d286859 -->
## Customer login

> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/auth/login" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"email":"ut","password":"occaecati"}'

```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/auth/login`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `email` | string |  required  | 
        `password` | string |  required  | 
    
<!-- END_a925a8d22b3615f12fca79456d286859 -->

<!-- START_2e1c96dcffcfe7e0eb58d6408f1d619e -->
## Customer registration

> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/auth/register" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"firstname":"tempore","lastname":"saepe","email":"veritatis","phone":"architecto","password":"delectus","dob":"facilis"}'

```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/auth/register`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `firstname` | string |  required  | 
        `lastname` | string |  optional  | requird
        `email` | string |  required  | 
        `phone` | string |  required  | 
        `password` | string |  required  | 
        `dob` | date |  optional  | optional format yyyy-mm-dd
    
<!-- END_2e1c96dcffcfe7e0eb58d6408f1d619e -->

<!-- START_a5c6310c3509d478f05f37ef97fbd242 -->
## Update customer profile

> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/profile/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"firstname":"quam","lastname":"sit","middlename":"alias","email":"ullam","phone":"consequuntur","password":"omnis","dob":"sunt","address":"quo","state":"veritatis","city":"voluptate","religion":"assumenda","gender":"omnis","height":"vitae","weight":"ab"}'

```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/profile/update`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `firstname` | string |  required  | 
        `lastname` | string |  required  | 
        `middlename` | string |  optional  | 
        `email` | string |  required  | 
        `phone` | string |  required  | 
        `password` | string |  required  | 
        `dob` | date |  optional  | optional format yyyy-mm-dd
        `address` | string |  optional  | 
        `state` | string |  optional  | 
        `city` | string |  optional  | 
        `religion` | string |  optional  | 
        `gender` | string |  optional  | optional male or female
        `height` | numeric |  optional  | 
        `weight` | numeric |  optional  | 
    
<!-- END_a5c6310c3509d478f05f37ef97fbd242 -->

<!-- START_b71a5a02aab383a384b96d685c0f023c -->
## Upload picture

Upload customer profile picture

> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/profile/picture" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"picture":"dolorem"}'

```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/profile/picture`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `picture` | file |  required  | image file
    
<!-- END_b71a5a02aab383a384b96d685c0f023c -->

<!-- START_82c763c1bcc6e68dfb2a9f4407a4147c -->
## Health history

Fetch customer's health history data

> Example request:

```bash
curl -X GET \
    -G "http://206.189.236.121:3000/api/profile/health-history" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Token not provided"
}
```

### HTTP Request
`GET api/profile/health-history`


<!-- END_82c763c1bcc6e68dfb2a9f4407a4147c -->

<!-- START_11a59c912a841991391d595f736a5a02 -->
## Change password

Change customer password

> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/password/change" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"current_password":"vero","new_password":"ea"}'

```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/password/change`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `current_password` | string |  required  | 
        `new_password` | string |  required  | 
    
<!-- END_11a59c912a841991391d595f736a5a02 -->

<!-- START_b074507f1af675135b19025749d6404d -->
## Send customer message

> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/contact/message" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"message":"ut"}'

```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/contact/message`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `message` | string |  required  | 
    
<!-- END_b074507f1af675135b19025749d6404d -->

<!-- START_774744abc65e28e4368f69ef4798a8f7 -->
## Doctors

Fetch paged list of doctors

> Example request:

```bash
curl -X GET \
    -G "http://206.189.236.121:3000/api/doctors" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Token not provided"
}
```

### HTTP Request
`GET api/doctors`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `page` |  optional  | int optional defaults to 1

<!-- END_774744abc65e28e4368f69ef4798a8f7 -->

<!-- START_2a0648ad28005a38e1533bf9aae50c45 -->
## Health tips

> Example request:

```bash
curl -X GET \
    -G "http://206.189.236.121:3000/api/health-tips" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Token not provided"
}
```

### HTTP Request
`GET api/health-tips`


<!-- END_2a0648ad28005a38e1533bf9aae50c45 -->

<!-- START_6ce56d17034a5db188b17f9607f9699a -->
## Health centers

Fetch paged list of health centers

> Example request:

```bash
curl -X GET \
    -G "http://206.189.236.121:3000/api/health-centers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Token not provided"
}
```

### HTTP Request
`GET api/health-centers`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `page` |  optional  | int optional defaults to 1

<!-- END_6ce56d17034a5db188b17f9607f9699a -->

<!-- START_af23ab2f5d60687f1d48a54ba6071156 -->
## Book appointment

> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/appointments/book" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"medical_center":"dolor","reason":"modi","date":"eos","time":"expedita"}'

```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/appointments/book`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `medical_center` | uuid |  required  | a health center uuid
        `reason` | string |  required  | the purpose of the appointment
        `date` | date |  optional  | format yyyy-mm-dd
        `time` | time |  optional  | format HH:mm
    
<!-- END_af23ab2f5d60687f1d48a54ba6071156 -->

<!-- START_5341eae881b35d79be68556d28e4a5ca -->
## View Appointment

View details of an appointment

> Example request:

```bash
curl -X GET \
    -G "http://206.189.236.121:3000/api/appointments/view" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```


> Example response (401):

```json
{
    "message": "Token not provided"
}
```

### HTTP Request
`GET api/appointments/view`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `uuid` |  optional  | uuid required the uuid of the appointment

<!-- END_5341eae881b35d79be68556d28e4a5ca -->

<!-- START_0c948b2c4b87e21dda8acfc98d983043 -->
## Update appointment

Update the details of an appointment

> Example request:

```bash
curl -X PUT \
    "http://206.189.236.121:3000/api/appointments/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"uuid":"ratione","medical_center":"ut","reason":"enim","date":"atque","time":"repudiandae"}'

```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/appointments/update`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `uuid` | uuid |  required  | the uuid of the appointment
        `medical_center` | uuid |  required  | a health center uuid
        `reason` | string |  required  | the purpose of the appointment
        `date` | date |  optional  | format yyyy-mm-dd
        `time` | time |  optional  | format HH:mm
    
<!-- END_0c948b2c4b87e21dda8acfc98d983043 -->

<!-- START_994d0ebdd441457bafe52e56a2e8251d -->
## Cancel appointment

> Example request:

```bash
curl -X PUT \
    "http://206.189.236.121:3000/api/appointments/cancel" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/appointments/cancel`

#### URL Parameters

Parameter | Status | Description
--------- | ------- | ------- | -------
    `uuid` |  optional  | uuid required the uuid of the appointment

<!-- END_994d0ebdd441457bafe52e56a2e8251d -->

<!-- START_ce6c124d37580e948c6bdbac094f2705 -->
## Update customer profile

> Example request:

```bash
curl -X PUT \
    "http://206.189.236.121:3000/api/nello/profile/update" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"firstname":"aliquam","lastname":"sed","middlename":"incidunt","email":"officia","phone":"veniam","password":"odio","dob":"dolorum","address":"et","state":"nostrum","city":"omnis","religion":"aut","gender":"enim","height":"omnis","weight":"perspiciatis"}'

```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`PUT api/nello/profile/update`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `firstname` | string |  required  | 
        `lastname` | string |  required  | 
        `middlename` | string |  optional  | 
        `email` | string |  required  | 
        `phone` | string |  required  | 
        `password` | string |  required  | 
        `dob` | date |  optional  | optional format yyyy-mm-dd
        `address` | string |  optional  | 
        `state` | string |  optional  | 
        `city` | string |  optional  | 
        `religion` | string |  optional  | 
        `gender` | string |  optional  | optional male or female
        `height` | numeric |  optional  | 
        `weight` | numeric |  optional  | 
    
<!-- END_ce6c124d37580e948c6bdbac094f2705 -->

<!-- START_404f60051b349e142f2036e459322283 -->
## Upload picture

Upload customer profile picture

> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/nello/profile/picture" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json" \
    -d '{"picture":"debitis"}'

```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/nello/profile/picture`

#### Body Parameters
Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    `picture` | file |  required  | image file
    
<!-- END_404f60051b349e142f2036e459322283 -->

<!-- START_9cd3adc2d93e72380e357a58e53eea16 -->
## api/import/users
> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/import/users" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/import/users`

`PUT api/import/users`

`DELETE api/import/users`


<!-- END_9cd3adc2d93e72380e357a58e53eea16 -->

<!-- START_13c25adebfbbf4054c2d1c99e12d7ada -->
## api/import/health-centers
> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/import/health-centers" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/import/health-centers`


<!-- END_13c25adebfbbf4054c2d1c99e12d7ada -->

<!-- START_d4e4b9e8970c4d7b6839f057fc39c48e -->
## api/import/encounter
> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/import/encounter" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/import/encounter`


<!-- END_d4e4b9e8970c4d7b6839f057fc39c48e -->

<!-- START_7b2effbeae57f65c5f37a728b4d199d6 -->
## api/import/health-tip
> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/import/health-tip" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/import/health-tip`


<!-- END_7b2effbeae57f65c5f37a728b4d199d6 -->

<!-- START_21894a7279a96eb271bbe767f2680d63 -->
## api/import/investigation
> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/import/investigation" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/import/investigation`


<!-- END_21894a7279a96eb271bbe767f2680d63 -->

<!-- START_721d4db2684e133bd1ac31accef9ddd8 -->
## api/import/invoice
> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/import/invoice" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/import/invoice`


<!-- END_721d4db2684e133bd1ac31accef9ddd8 -->

<!-- START_753074fd64f26425567a063c8d4ec54c -->
## api/import/medication
> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/import/medication" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/import/medication`


<!-- END_753074fd64f26425567a063c8d4ec54c -->

<!-- START_3113f2fe3db04c61f83b927f3bb67000 -->
## api/import/payment
> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/import/payment" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/import/payment`


<!-- END_3113f2fe3db04c61f83b927f3bb67000 -->

<!-- START_05d7f9296d41661f4f22f19e63196d53 -->
## api/import/procedure
> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/import/procedure" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/import/procedure`


<!-- END_05d7f9296d41661f4f22f19e63196d53 -->

<!-- START_c7fd933a546ecaa6ae13ce265060245c -->
## api/import/vital
> Example request:

```bash
curl -X POST \
    "http://206.189.236.121:3000/api/import/vital" \
    -H "Content-Type: application/json" \
    -H "Accept: application/json"
```

```javascript
const url = new URL(
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
    .then(response => response.json())
    .then(json => console.log(json));
```



### HTTP Request
`POST api/import/vital`


<!-- END_c7fd933a546ecaa6ae13ce265060245c -->


