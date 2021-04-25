let rating = document.getElementById('rating')

var rating_colors = ['#999999', '#FF0000', '#fe4400', '#f86600', '#ee8200', 
                     '#df9b00', '#cdb200', '#b6c700', '#98db00', '#6fed00', '#00ff00']

if (rating) {
  let rating_int = parseInt(Number(rating.innerHTML))
  rating.style.color = rating_colors[rating_int]
}

function like_report(report_id) {
  send_feedback(report_id, 'like', `#l${report_id}`)
}

function dislike_report(report_id) {
  send_feedback(report_id, 'dislike', `#d${report_id}`)
}

function send_feedback(report_id, type, counter) {
  json_body = {report_id: report_id, type: type}
  $.ajax({
    type: "POST",
    url: '/send_feedback',
    data: json_body,
    error: function (data) {
      window.location.replace('/login')
    },
    success: function (data) {
      $(counter).html(Number($(counter).html()) + 1)
    }
  })
}

window.rating_colors = rating_colors
window.like_report = like_report
window.dislike_report = dislike_report