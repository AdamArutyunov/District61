let rating = document.getElementById('rating')

if (rating) {
  let rating_int = parseInt(Number(rating.innerHTML))

  rating_colors = ['grey', '#FF0000', '#fe4400', '#f86600', '#ee8200', 
                  '#df9b00', '#cdb200', '#b6c700', '#98db00', '#6fed00', '#00ff00']

  rating.style.color = rating_colors[rating_int]
}