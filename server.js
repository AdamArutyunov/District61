const express = require('express')
const reload = require('reload')
const http = require('http')
const fetch = require('node-fetch')
const bodyParser = require("body-parser");
const cookieParser = require('cookie-parser');
const app = express()
const DEBUG = (process.argv[2] == "-D")

var config = require("./config.json")
const { request, response } = require('express');
const { rejects } = require('assert');
const port = config.PORT

const urlencodedParser = bodyParser.urlencoded({extended: false});

app.set('views', './src/pug')
app.set('view engine', 'pug');
app.use('/static', express.static('static'));
app.use(cookieParser())

app.get('/forward/:path', (request, response) => {
    return fetch(config.API_ENDPOINT + request.params.path).then(result => result.json()).then(
        result => response.send(result)
    )
})

function send_report(json_body) {
    return fetch(config.API_ENDPOINT + 'report/create', {
        method: 'post',
        body: JSON.stringify(json_body),
        headers: {'Content-Type': 'application/json'}
    })
}

app.get('/', (request, response) => {
    fetch(config.API_ENDPOINT + 'districts').then(
        result => result.json()
    ).then(
        districts => response.render('index', {title: 'District 61', districts: districts})
    )
})

app.get('/district/:districtId', (request, response) => {
    fetch(config.API_ENDPOINT + 'districts/' + request.params.districtId).then(
        result => result.json()
    ).then(
        district => response.render('district', {title: district.name, district: district})
    )
})

app.get('/district/:districtId/report_good', (request, response) => {
    if (!request.cookies.token) return response.redirect('/login')
    response.render('report', {action: 'good', title: 'Похвалить'})
})

app.get('/district/:districtId/report_bad', (request, response) => {
    if (!request.cookies.token) return response.redirect('/login')
    response.render('report', {action: 'bad', title: 'Поругать'})
})

app.post('/district/:districtId/report_good', urlencodedParser, (request, response) => {
    if (!request.cookies.token) response.redirect('/login')
    json_body = {
        district_id: request.params.districtId,
        body: request.body.body,
        is_good: 1,
        token: request.cookies.token
    }
    send_report(json_body).then(
        result => response.redirect(`/district/${request.params.districtId}`)
    )
})

app.post('/district/:districtId/report_bad', urlencodedParser, (request, response) => {
    if (!request.cookies.token) response.redirect('/login')
    json_body = {
        district_id: request.params.districtId,
        body: request.body.body,
        is_good: 0,
        token: request.cookies.token
    }
    console.log(request.cookies.token)
    send_report(json_body).then(
        result => {
            console.log(result)
            response.redirect(`/district/${request.params.districtId}`)
        }
    )
})

app.post('/send_feedback', urlencodedParser, (request, response) => {
    if (!request.cookies.token) response.sendStatus(401)
    request.body.token = request.cookies.token
    fetch(config.API_ENDPOINT + 'report/createFeedback', {
        method: 'post',
        body: JSON.stringify(request.body),
        headers: {'Content-Type': 'application/json'}
    }).then(res => {
        response.sendStatus(200)
    })
})

app.get('/login', (request, response) => {
    response.render('login')
})

app.post('/login', urlencodedParser, (request, response) => {
    var email = request.body.email
    var password = request.body.password

    json_data = {
        email: email,
        password: password
    }

    fetch(config.API_ENDPOINT + 'auth/login', {
        method: 'post',
        body: JSON.stringify(json_data),
        headers: {'Content-Type': 'application/json'}
    }).then(res => res.json()).then(res => {
        if (res.token) {
            response.cookie('token', res.token, {maxAge: 3600 * 1000, httpOnly: true})
            response.redirect('/')
        } else {
            response.render('login', {message: 'НЕПРАВИЛЬНЫЙ ЛОГИН ИЛИ ПАРОЛЬ!'})
        }
    })
})

app.get('/register', (request, response) => {
    response.render('register')
})

app.post('/register', urlencodedParser, (request, response) => {
    var email = request.body.email
    var login = request.body.login
    var password = request.body.password

    json_data = {
        email: email,
        login: login,
        password: password
    }

    fetch(config.API_ENDPOINT + 'auth/register', {
        method: 'post',
        body: JSON.stringify(json_data),
        headers: {'Content-Type': 'application/json'}
    }).then(res => res.json()).then(res => {
        console.log(res.token)
        response.cookie('token', res.token, {maxAge: 3600 * 1000, httpOnly: true})
        response.redirect('/')
    })
})

app.use(function error_handler(error, request, response, next) {
    console.log(error)
    response.render('error', {error: error})
})


if (DEBUG) {
    var server = http.createServer(app)

    reload(app).then(function (reloadReturned) {
        server.listen(port, function () {
        console.log('Debug server listening on port ' + port)
        })
    })
} else {
    app.listen(port, () => {
        console.log(`Example app listening at http://localhost:${port}`)
    })
}


