const express = require('express')
const reload = require('reload')
const http = require('http')
const app = express()
const DEBUG = (process.argv[2] == "-D")

var config = require("./config.json")
const port = config.port

app.set('views', './src/pug')
app.set('view engine', 'pug');
app.use('/static', express.static('static'));


app.get('/', (req, res) => {
    res.render('index', {title: "District 61"});
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


