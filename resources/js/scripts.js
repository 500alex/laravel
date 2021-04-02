setTimeout(custom, 5)

function custom() {
  let el = $(".catalog-button")
  let catalog = $(".catalog-wrap")

  el.click(function() {
    if (!el.hasClass("catalog-show")) {
      el.addClass("catalog-show")
      catalog.addClass("show")
    } else {
      el.removeClass("catalog-show")
      catalog.removeClass("show")
    }
  })
}



