const express = require('express')
const reload = require('reload')
const http = require('http')
const fetch = require('node-fetch')
const bodyParser = require("body-parser");
const app = express()
const DEBUG = (process.argv[2] == "-D")

var config = require("./config.json")
const { request, response } = require('express')
const port = config.PORT

const urlencodedParser = bodyParser.urlencoded({extended: false});

app.set('views', './src/pug')
app.set('view engine', 'pug');
app.use('/static', express.static('static'));

function send_report(districtId, body, is_good) {
    let json_body = {
        district_id: districtId,
        body: body,
        is_good: is_good
    }

    console.log(json_body)

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
    response.render('report', {action: 'good', title: 'Похвалить'})
})

app.get('/district/:districtId/report_bad', (request, response) => {
    response.render('report', {action: 'bad', title: 'Поругать'})
})

app.post('/district/:districtId/report_good', urlencodedParser, (request, response) => {
    districtId = request.params.districtId
    send_report(districtId, request.body.body, 1).then(
        result => response.redirect(`/district/${districtId}`)
    )
})

app.post('/district/:districtId/report_bad', urlencodedParser, (request, response) => {
    districtId = request.params.districtId
    send_report(districtId, request.body.body, 0).then(
        result => response.redirect(`/district/${districtId}`)
    )
})

app.post('/send_feedback', urlencodedParser, (request, response) => {
    fetch(config.API_ENDPOINT + 'report/createFeedback', {
        method: 'post',
        body: JSON.stringify(request.body),
        headers: {'Content-Type': 'application/json'}
    }).then(res => response.sendStatus(200))
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


