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
[Get Postman Collection](http://localhost/docs/collection.json)

<!-- END_INFO -->

#general
<!-- START_39d63b10792f0c0ea1365e1d7bf70c67 -->
## /api/fruits

> Example request:

```bash
curl "http://localhost//api/fruits" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost//api/fruits",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "exception": null,
    "original": [
        {
            "id": 1,
            "name": "apple",
            "color": "green",
            "weight": 150,
            "delicious": 1,
            "created_at": "2017-01-30 13:24:52",
            "updated_at": "2017-01-30 13:24:52"
        },
        {
            "id": 2,
            "name": "banana",
            "color": "yellow",
            "weight": 116,
            "delicious": 1,
            "created_at": "2017-01-30 13:24:52",
            "updated_at": "2017-01-30 13:24:52"
        },
        {
            "id": 3,
            "name": "strawberries",
            "color": "red",
            "weight": 12,
            "delicious": 1,
            "created_at": "2017-01-30 13:24:52",
            "updated_at": "2017-01-30 13:24:52"
        }
    ],
    "headers": {}
}
```

### HTTP Request
`GET /api/fruits`

`HEAD /api/fruits`


<!-- END_39d63b10792f0c0ea1365e1d7bf70c67 -->
<!-- START_2d2affccdcaf491480795c1ee436039b -->
## /api/fruits/{id}

> Example request:

```bash
curl "http://localhost//api/fruits/{id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost//api/fruits/{id}",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
{
    "exception": null,
    "original": {
        "id": 3,
        "name": "strawberries",
        "color": "red",
        "weight": 12,
        "delicious": 1,
        "created_at": "2017-01-30 13:24:52",
        "updated_at": "2017-01-30 13:24:52"
    },
    "headers": {}
}
```

### HTTP Request
`GET /api/fruits/{id}`

`HEAD /api/fruits/{id}`


<!-- END_2d2affccdcaf491480795c1ee436039b -->
<!-- START_d5417ec5d425f04b71e9a4e9987c8295 -->
## /api/authenticate

> Example request:

```bash
curl "http://localhost//api/authenticate" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost//api/authenticate",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST /api/authenticate`


<!-- END_d5417ec5d425f04b71e9a4e9987c8295 -->
<!-- START_a5d7bfde9c5e33e7c8fd6f07a11939b5 -->
## /api/authenticated_user

> Example request:

```bash
curl "http://localhost//api/authenticated_user" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost//api/authenticated_user",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET /api/authenticated_user`

`HEAD /api/authenticated_user`


<!-- END_a5d7bfde9c5e33e7c8fd6f07a11939b5 -->
<!-- START_fde36329ab58ad5d6ab50b7704de548b -->
## /api/token

> Example request:

```bash
curl "http://localhost//api/token" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost//api/token",
    "method": "GET",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```

> Example response:

```json
null
```

### HTTP Request
`GET /api/token`

`HEAD /api/token`


<!-- END_fde36329ab58ad5d6ab50b7704de548b -->
<!-- START_39798dab89951f0e0c3fc59a53f859e5 -->
## /api/logout

> Example request:

```bash
curl "http://localhost//api/logout" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost//api/logout",
    "method": "POST",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST /api/logout`


<!-- END_39798dab89951f0e0c3fc59a53f859e5 -->
<!-- START_7911c9ccc13ab3b52c29a3f7473bfa19 -->
## /api/fruits

> Example request:

```bash
curl "http://localhost//api/fruits" \
-H "Accept: application/json" \
    -d "name"="ut" \
    -d "color"="ut" \
    -d "weight"="21" \
    -d "delicious"="1" \

```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost//api/fruits",
    "method": "POST",
    "data": {
        "name": "ut",
        "color": "ut",
        "weight": 21,
        "delicious": true
},
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`POST /api/fruits`

#### Parameters

Parameter | Type | Status | Description
--------- | ------- | ------- | ------- | -----------
    name | string |  required  | 
    color | string |  required  | Only alphabetic characters allowed
    weight | numeric |  required  | 
    delicious | boolean |  required  | 

<!-- END_7911c9ccc13ab3b52c29a3f7473bfa19 -->
<!-- START_e839e2c58cf3045a3add8543207c9de4 -->
## /api/fruits/{id}

> Example request:

```bash
curl "http://localhost//api/fruits/{id}" \
-H "Accept: application/json"
```

```javascript
var settings = {
    "async": true,
    "crossDomain": true,
    "url": "http://localhost//api/fruits/{id}",
    "method": "DELETE",
    "headers": {
        "accept": "application/json"
    }
}

$.ajax(settings).done(function (response) {
    console.log(response);
});
```


### HTTP Request
`DELETE /api/fruits/{id}`


<!-- END_e839e2c58cf3045a3add8543207c9de4 -->
