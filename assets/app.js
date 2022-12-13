import 'jquery';


import './styles/app.css';

// start the Stimulus application
import './bootstrap';

//************** Auto-complete  **************************/
$(document).ready(function () {
  const $input = $("#myInput");
  $input.keyup(function (e) {
    if (e.target.value.length > 1) {
      clearTimeout(timer);
      
      let timer = setTimeout(function () {
        $.get("http://127.0.0.1:8000/ajax/movies/search/" + e.target.value, function (dataList) {
          $('#auto-complete').replaceWith(dataList);
          $("#auto-complete").show();
        });
      }, 1000);
    }

  });
  $input.focusout(function (e) {
    $("#auto-complete").hide();
  });
});
//************************************************** */


//************ genre select ************************/
$('.checkbox').click(function () {
  var genres = [];
  $('#checkBar input:checked').each(
    function () {
      genres.push($(this)[0].value)
    }

  );
  console.log(genres.join(','))
  $.get("http://127.0.0.1:8000/ajax/movies/" + genres.join(','), function (data) {
    $('.main').replaceWith(data);
  });

});
//************************************************** */

//*************** details display  *******************/
var modal = document.getElementById("myModal");


var btn = document.getElementsByClassName("card");



btn.onclick = function () {
  modal.style.display = "block";
}


window.onclick = function (event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

$(document).on("click", '.card', function (e) {
  console.log("card click testtttt");
  $.get("http://127.0.0.1:8000/ajax/movies/details/" + e.currentTarget.id, function (data) {
    console.log("movie data")
    $('.modal-content').replaceWith(data);
    modal.style.display = "block";
  });
});
//************************************************************ */

$(document).on("click",'.movie-item', function (e) {
  console.log("search item click");
  $.get("http://127.0.0.1:8000/ajax/movies/details/" + e.target.id, function (data) {
    console.log("test click item")
    // $('.modal-content').replaceWith(data);
    // modal.style.display = "block";
  });

});