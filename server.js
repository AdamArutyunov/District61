const express = require('express')
const reload = require('reload')
const http = require('http')
const fetch = require('node-fetch')
const app = express()
const DEBUG = (process.argv[2] == "-D")

var config = require("./config.json")
const { request } = require('express')
const port = config.PORT

app.set('views', './src/pug')
app.set('view engine', 'pug');
app.use('/static', express.static('static'));


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


